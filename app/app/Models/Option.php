<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Option extends Model
{
    use HasFactory, SoftDeletes, Filterable;

    protected $guarded = [];

    /** RELATIONS */

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
     * AUTHOR
     */
    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id')->withTrashed();
    }



    public function prices()
    {
        return $this->hasMany(\App\Models\OptionPrice::class, 'option_id', 'id')->orderBy('id', 'DESC');
    }



    public function current_price()
    {
        return $this->hasOne(\App\Models\OptionCurrentPrice::class, 'option_id', 'id')->withDefault();
    }



    /**
     * ACCESSORS
     */

    public function getPriceAttribute()
    {
        return $this->current_price->price ?? 0;
    }



    public function getCurrentBeginDateAttribute()
    {
        if ($this->current_price && $this->current_price->option_price)
            return $this->current_price->option_price->begin_at->format('d.m.Y ');
        return null;
    }
}
