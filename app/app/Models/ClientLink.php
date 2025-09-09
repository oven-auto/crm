<?php

namespace App\Models;

use App\Models\Interfaces\CommentInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientLink extends Model implements CommentInterface
{
    use HasFactory;

    protected $guarded = [];

    public function writeComment(array $data)
    {
        ClientComment::create($data);
    }

    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id')->withDefault()->withTrashed();
    }
}
