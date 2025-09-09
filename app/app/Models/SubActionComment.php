<?php

namespace App\Models;

use App\Models\Interfaces\IAmComment;
use App\Models\Traits\GetCommentData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubActionComment extends Model implements IAmComment
{
    use HasFactory, GetCommentData;

    protected $guarded = [];

    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id' ,'author_id')->withTrashed();
    }
}
