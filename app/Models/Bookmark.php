<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'metadata_id',
        'title',
        'page_number',
    ];

    public $children = [];

    public function GetLastChildDepth($i)
    {
        if ($i <= 1) {
            return $this;
        }

        return end($this->children)->GetLastChildDepth($i - 1);
    }

    public function SaveChildren()
    {
        if (empty($this->children)) {
            return;
        }

        foreach ($this->children as $child) {
            $child->parent_id = $this->id;
            $child->metadata_id = $this->metadata_id;
            $child->save();

            $child->SaveChildren();
        }
    }

    public function parent()
    {
        return $this->belongsTo(Bookmark::class);
    }

    public function metadata()
    {
        return $this->belongsTo(Metadata::class);
    }

    public function GetLevel($i = 0)
    {
        $i++;

        if ($this->parent == null) {
            return $i;
        } else {
            return $this->parent->GetLevel($i);
        }
    }

    public function LoadChildren() {
        $bookmarks = Bookmark::where('metadata_id', $this->id)
            ->where('parent_id', $this->id)
            ->get()
            ->toArray();

        $this->children = $bookmarks;
        foreach ($this->children as $child) {
            $child->LoadChildren();
        }
    }

    public function GetBookmarkText()
    {
        return [
            'BookmarkBegin',
            "BookmarkTitle: $this->title",
            "BookmarkLevel: $this->GetLevel()",
            "BookmarkPageNumber: $this->page_number",
        ];
    }

    public function GetChildrenBookmarks() {
        $out = array();

        if (empty($this->children)) {
            return [];
        }

        foreach ($this->children as $child) {
            $out = array_merge($out, $child->GetBookmarkText);
            $out = array_merge($child->GetChildrenBookmarks());
        }

        return $out;
    }
}
