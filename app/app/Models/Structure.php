<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Interfaces\GiveDataForCommentInterface;

class Structure extends Model implements GiveDataForCommentInterface
{
    use HasFactory;

    public $timestamps = false;

    public function forComment()
    {
        return $this->name;
    }
}
