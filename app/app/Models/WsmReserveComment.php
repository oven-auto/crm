<?php

namespace App\Models;

use App\Models\Interfaces\IAmComment;
use App\Models\Traits\GetCommentData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WsmReserveComment extends Model implements IAmComment
{
    use HasFactory, GetCommentData;

    protected $guarded = [];

    protected $with = ['author'];

    /**
     * RELATIONS
     */

    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id')->withDefault()->withTrashed();
    }



    public function reserve()
    {
        return $this->hasOne(\App\Models\WsmReserveNewCar::class, 'id', 'reserve_id');
    }
}
