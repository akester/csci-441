<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class Document extends Model
{
    use HasFactory;

    public static $STORAGE_POOL = 'documents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'filename',
        'uuid',
        'path',
    ];

    public function Owner()
    {
        return $this->belongsTo(User::class);
    }

    public function metadata()
    {
        return $this->hasOne(Metadata::class, 'document_id');
    }

    /**
     * Upload and store a file uploaded from a user
     */
    public function Upload(UploadedFile $file)
    {
        // Set a UUID
        $uuid = UuidV4::uuid4()->toString();
        $this->uuid = $uuid;

        // Store the file into our local storage, and save out a path.
        $path = Storage::putFileAs(self::$STORAGE_POOL, $file, $uuid);
        $this->path = $path;

        // Store the original filename from when we uploaded it.
        $this->filename = $file->getClientOriginalName();

        // Push it to the database.
        $this->save();
    }

    public function Download()
    {
        return Storage::download($this->path, $this->filename);
    }

    public function GetMetadata()
    {
        $process = new Process([base_path() . '/pdftk', storage_path('app/' . $this->path), 'dump_data']);
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $metadataText = $process->getOutput();
        $metadataArray = explode(PHP_EOL, $metadataText);
        $bookmarks = [];

        // We do a for loop so we can skip lines once we detect a bookmark.
        for ($i = 0; $i < count($metadataArray); $i++) {
            $line = $metadataArray[$i];

            if ($line == "BookmarkBegin") {
                $bookmark = new Bookmark();

                $hasTitle = false;
                $hasLevel = false;
                $hasPage = false;

                for ($j = 1; $j <= 3; $j++) {
                    $nextLine = $metadataArray[$j + $i];

                    if (substr($nextLine, 0, 13) == 'BookmarkLevel') {
                        $level = substr($nextLine, 15);
                        $hasLevel = true;
                    }
                    if (substr($nextLine, 0, 13) == 'BookmarkTitle') {
                        $bookmark->title = substr($nextLine, 15);
                        $hasTitle = true;
                    }
                    if (substr($nextLine, 0, 18) == 'BookmarkPageNumber') {
                        $bookmark->page_number = substr($nextLine, 20);
                        $hasPage = true;
                    }
                }

                if (!$hasLevel || !$hasPage || !$hasTitle) {
                    continue;
                }

                if ($level == 1) {
                    $bookmarks[] = $bookmark;
                } else {
                    // This will recurse down to the level and append the current bookmark
                    end($bookmarks)->GetLastChildDepth($level - 1)->children[] = $bookmark;
                }
            }
        }

        $metadata =  new Metadata(
            [
                'metadata' => $metadataText,
                'document_id' => $this->id,
            ]
        );
        $metadata->bookmarks = $bookmarks;

        return $metadata;
    }

    public function SaveMetadata()
    {
        // Rebuild the metadata text file
        $this->metadata->LoadBookmarks();
        $newMetadata = $this->metadata->GetMetadata();

        // Create a new file to store our temp metadata
        $file = tmpfile();
        $path = stream_get_meta_data($file)['uri'];

        fwrite($file, $newMetadata);

        copy (storage_path('app/' . $this->path), storage_path('app/' . $this->path . '.old'));
        $process = new Process([
            base_path() . '/pdftk',
            storage_path('app/' . $this->path . '.old'), 
            'update_info_utf8',
            $path,
            'output',
            storage_path('app/' . $this->path)
        ]);
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

    }
}
