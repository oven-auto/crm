<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Interfaces\GiveDataForCommentInterface;

class Appeal extends Model implements GiveDataForCommentInterface
{
    use HasFactory;

    public $timestamps = false;

    public function trafic_products()
    {
        return $this->hasMany(\App\Models\TraficProduct::class, 'appeal_id', 'id')->orderBy('group_id')->orderBy('name');
    }

    public function forComment()
    {
        return $this->name;
    }
}
