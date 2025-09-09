<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Interfaces\GiveDataForCommentInterface;

class TraficChanel extends Model implements GiveDataForCommentInterface
{
    use HasFactory;

    public $timestamps = false;

    public function childrens(){
        return $this->hasMany(\App\Models\TraficChanel::class, 'parent', 'id')->orderBy('sort');
    }

    public function myparent()
    {
        return $this->hasOne(\App\Models\TraficChanel::class, 'id', 'parent')->withDefault();
    }

    public function forComment()
    {
        return $this->name;
    }
}
