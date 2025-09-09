<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Createable;

class Brand extends Model
{
    use HasFactory, Createable;

    protected $fillable = ['name', 'slug', 'icon', 'brand_color', 'font_color', 'uid'];

    public $timestamps = false;

    public function getIconDateAttribute()
    {
    	return asset('storage/'.$this->icon) . '?' . date('dmyhm');
    }



    public function dnm()
    {
        return $this->hasOne(\App\Models\DnmBrand::class, 'brand_id', 'id')->withDefault();
    }


}
