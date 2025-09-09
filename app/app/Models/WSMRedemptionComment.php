<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Interfaces\IAmComment;
use App\Models\Traits\GetCommentData;

class WSMRedemptionComment extends Model implements IAmComment
{
    use HasFactory, GetCommentData;

    protected $guarded = [];

    protected $table = 'wsm_redemption_comments';

    protected $with = ['author'];

    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id' ,'author_id')->withTrashed();
    }
}
