<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Interfaces\GiveDataForCommentInterface;

class ClientType extends Model
{
    use HasFactory;

    public function forComment()
    {
        return $this->name;
    }
}
