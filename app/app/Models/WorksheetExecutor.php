<?php

namespace App\Models;

use App\Models\Interfaces\CommentInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorksheetExecutor extends Model implements CommentInterface
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function writeComment(array $data)
    {
        return WorksheetActionComment::create($data);
    }
}
