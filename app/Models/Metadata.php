<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metadata extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'metadata',
    ];

    public $bookmarks = [];

    public function document() {
        return $this->belongsTo(Document::class);
    }

    public function SaveBookmarks() {
        foreach ($this->bookmarks as $bookmark) {
            $bookmark->metadata_id = $this->id;
            $bookmark->save();
            
            $bookmark->SaveChildren();
        }
    }
}
