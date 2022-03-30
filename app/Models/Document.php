<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Rfc4122\UuidV4;

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
        'Filename',
        'Uuid',
        'Path',
    ];

    public function Owner() {
        return $this->belongsTo(User::class);
    }

    /**
     * Upload and store a file uploaded from a user
     */
    public function Upload(UploadedFile $file) {
        // Set a UUID
        $uuid = UuidV4::uuid4()->toString();
        $this->Uuid = $uuid;

        // Store the file into our local storage, and save out a path.
        $path = Storage::putFileAs(self::$STORAGE_POOL, $file, $uuid);
        $this->Path = $path;

        // Store the original filename from when we uploaded it.
        $this->Filename = $file->getClientOriginalName();

        // Push it to the database.
        $this->save();
    }

    public function Download() {
        return Storage::download($this->Path, $this->Filename);
    }
}
