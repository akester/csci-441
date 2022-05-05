<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JsonSerializable;

class Bookmark extends Model implements JsonSerializable
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
        $bookmarks = Bookmark::where('metadata_id', $this->metadata_id)
            ->where('parent_id', $this->id)
            ->get();

        $this->children = $bookmarks;
        foreach ($this->children as $child) {
            $child->LoadChildren();
        }
    }

    public function GetBookmarkText()
    {
        $level = $this->GetLevel();

        return [
            'BookmarkBegin',
            "BookmarkTitle: $this->title",
            "BookmarkLevel: $level",
            "BookmarkPageNumber: $this->page_number",
        ];
    }

    public function GetChildrenBookmarks() {
        $out = array();

        foreach ($this->children as $child) {
            $out = array_merge($out, $child->GetBookmarkText());
            $out = array_merge($out, $child->GetChildrenBookmarks());
        }

        return $out;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'page_number' => $this->page_number,
            'level' => $this->GetLevel(),
            'parent_id' => $this->parent_id,
        ];
    }
}
