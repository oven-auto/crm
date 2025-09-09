<?php

namespace App\Models;

use App\Models\Interfaces\CommentInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class WorksheetFile extends Model implements CommentInterface
{
    use HasFactory;

    protected $guarded = [];

    public function writeComment(array $data)
    {
        return WorksheetActionComment::create($data);
    }



    public function author()
    {
        return $this->hasOne(User::class, 'id', 'author_id')->withDefault();
    }



    public function worksheet()
    {
        return $this->hasOne(\App\Models\Worksheet::class, 'id', 'worksheet_id')->withDefault();
    }



    public function getFile()
    {
        if(isset($this->file) && Storage::disk('public')->exists($this->file))
            return asset('storage'.$this->file) . '?' . date('dmyhm');
    }


    
    public function getNameAttribute()
    {

        $arr = explode('/', $this->file);
        $name = array_pop( $arr );
        return $name;

    }
}
