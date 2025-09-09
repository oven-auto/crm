<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Filterable;
use App\Models\Traits\Createable;

class Color extends Model
{
    use HasFactory, Filterable, Createable;

    protected $guarded = [];

    public $timestamps = false;

    public function brand()
    {
        return $this->hasOne(\App\Models\Brand::class, 'id', 'brand_id');
    }

    // public function marks()
    // {

    // }
}
