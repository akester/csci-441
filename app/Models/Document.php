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

        echo $process->getOutput();
    }
}
