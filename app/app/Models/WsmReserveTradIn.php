<?php

namespace App\Models;

use App\Models\Interfaces\CommentInterface;
use App\Services\Comment\Comment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class WsmReserveTradIn extends Pivot implements CommentInterface
{
    use HasFactory;



    public function writeComment(array $data)
    {
        WsmReserveComment::create($data);
    }



    public static function boot()
    {
        parent::boot();

        static::created(function($item){
            Comment::add($item, 'store');
        });

        static::deleted(function($item){
            Comment::add($item, 'delete');
        });
    }
}
