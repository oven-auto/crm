<?php

namespace App\Models;

use App\Models\Interfaces\CommentInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorksheetLink extends Model implements CommentInterface
{
    use HasFactory;

    protected $guarded = [];


    
    public function writeComment(array $data)
    {
        return WorksheetActionComment::create($data);
    }



    public function worksheet()
    {
        return $this->hasOne(\App\Models\Worksheet::class, 'id', 'worksheet_id')->withDefault();
    }



    public function author()
    {
        return $this->hasOne(User::class, 'id', 'author_id')->withDefault();
    }
}
