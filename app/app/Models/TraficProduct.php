<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TraficProduct extends Model
{
    use HasFactory;

    public function group()
    {
        return $this->hasOne(\App\Models\ProductGroup::class,'id','group_id')->withDefault()->orderBy('sort');
    }



    public function getIdAttribute()
    {
        return $this->number;
    }
}
