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
    ];

    public $children = [];

    public function GetLastChildDepth($i) {
        if ($i <= 1) {
            return $this;
        }

        return end($this->children)->GetLastChildDepth($i - 1);
    }

    public function SaveChildren() {
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

    public function parent() {
        return $this->belongsTo(Bookmark::class);
    }

    public function metadata() {
        return $this->belongsTo(Metadata::class);
    }
}
