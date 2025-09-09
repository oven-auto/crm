<?php

namespace App\Models;

use App\Models\Interfaces\CommentInterface;
use App\Services\Comment\Comment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TraficControl extends Model implements CommentInterface
{
    use HasFactory;

    protected $casts = [
        'begin_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    protected $guarded = ['id'];

    public static function boot()
    {
        parent::boot();

        static::created(function($item){
            Comment::add($item, 'create');
        });

        static::updated(function($item){
            if($item->isDirty())
                Comment::add($item, 'update');
        });
    }



    public function writeComment(array $data)
    {
        return TraficComment::create($data);
    }
}
