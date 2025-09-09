<?php

namespace App\Models;

use App\Models\Interfaces\CommentInterface;
use App\Services\Comment\Comment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TraficLink extends Model implements CommentInterface
{
    use HasFactory;

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::created(function($item){
            Comment::add($item, 'create');
        });

        static::deleted(function($item){
            Comment::add($item, 'delete');
        });
    }



    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id')->withDefault()->withTrashed();
    }



    public function trafic()
    {
        return $this->hasOne(\App\Models\Trafic::class, 'id', 'trafic_id')->withDefault();
    }



    public function writeComment(array $data)
    {
        return TraficComment::create($data);
    }
}
