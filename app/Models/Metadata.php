<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JsonSerializable;

class Metadata extends Model implements JsonSerializable
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

    public function bookmarks_top_level() {
        return $this->hasMany(Bookmark::class, 'metadata_id');
    }

    public function SaveBookmarks() {
        foreach ($this->bookmarks as $bookmark) {
            $bookmark->metadata_id = $this->id;
            $bookmark->save();
            
            $bookmark->SaveChildren();
        }
    }

    public function SaveBookmarksArray() {
        $parents = [];
        $lastBookmark = null;

        foreach ($this->bookmarks as $bookmark) {
            $bookmarkObj = new Bookmark($bookmark);

            if ($bookmark['level'] > 1 && !is_null($lastBookmark)) {
                // Look for a parent for one level up
                $bookmarkObj->parent_id = $parents[$bookmark['level'] - 1]->id;
            }
            $bookmarkObj->metadata_id = $this->id;

            $bookmarkObj->save();
            $lastBookmark = $bookmarkObj;
            $parents[$bookmark['level']] = $bookmarkObj;
        }
    }

    public function LoadBookmarks() {
        $bookmarks = Bookmark::where('metadata_id', $this->id)
            ->whereNull('parent_id')
            ->get();

        foreach ($bookmarks as $bookmark) {
            $bookmark->LoadChildren();
        }

        $this->bookmarks = $bookmarks;
    }

    public function CleanBookmarkMetadata() {
        $out = [];

        foreach (explode(PHP_EOL, $this->metadata) as $line) {
            if (substr($line, 0, 13) == 'BookmarkLevel') {
                continue;
            }
            if (substr($line, 0, 13) == 'BookmarkTitle') {
                continue;
            }
            if (substr($line, 0, 18) == 'BookmarkPageNumber') {
                continue;
            }
            if ($line == "BookmarkBegin") {
                continue;
            }

            $out[] = $line;
        }

        return $out;
    }

    public function GetMetadata() {
        $currentMetadata = $this->CleanBookmarkMetadata();

        $bookmarks = [];
        $newMetadata = [];

        foreach ($this->bookmarks as $bookmark) {
            $bookmarks = array_merge($bookmark->GetBookmarkText());
            $bookmarks = array_merge($bookmarks, $bookmark->GetChildrenBookmarks());
        }

        foreach ($currentMetadata as $line) {
            if (substr($line, 0, 13) != 'NumberOfPages') {
                $newMetadata[] = $line;
                continue;
            }

            // Add this line
            $newMetadata[] = $line;
            // And add our bookmarks
            foreach ($bookmarks as $line) {
                $newMetadata[] = $line;
            }
        }

        return implode(PHP_EOL, $newMetadata);
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'bookmarks' => $this->bookmarks_top_level,
        ];
    }
}
