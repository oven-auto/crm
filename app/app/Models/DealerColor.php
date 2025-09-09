<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DealerColor extends Model
{
    use HasFactory, SoftDeletes, Filterable;

    protected $guarded = [];



    /**RELATIONS */

    /**
     * BRAND
     */
    public function brand()
    {
        return $this->hasOne(\App\Models\Brand::class, 'id', 'brand_id');
    }



    /**
     * MODEL
     */
    public function mark()
    {
        return $this->hasOne(\App\Models\Mark::class, 'id', 'mark_id');
    }



    /**
     * BASE COLOR
     */
    public function base()
    {
        return $this->hasOne(\App\Models\Color::class, 'id', 'base_id');
    }



    /**
     * AUTHOR
     */
    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id')->withTrashed();
    }



    public function images()
    {
        return $this->hasMany(\App\Models\DealerColorImage::class, 'dealer_color_id', 'id');
    }
}
