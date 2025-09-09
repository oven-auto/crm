<?php

namespace App\Models;

use App\Models\Interfaces\CommentInterface;
use App\Services\Comment\Comment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TraficFile extends Model implements CommentInterface
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


    
    public function getFile($param)
    {
        if(isset($this->$param) && Storage::disk('public')->exists($this->$param))
            return asset('storage/'.$this->$param) . '?' . date('dmyhm');
    }



    public function user()
    {
        return $this->hasOne(\App\Models\User::class,'id', 'user_id')->withTrashed();
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
