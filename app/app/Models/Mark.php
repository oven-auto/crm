<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Interfaces\SortInterface;
use App\Models\Traits\Createable;

class Mark extends Model implements SortInterface
{
    use HasFactory, Createable;

    protected $guarded = [];



    /**
     * БРЕНД
     */
    public function brand()
    {
        return $this->hasOne(\App\Models\Brand::class, 'id', 'brand_id')->withDefault();
    }



    public function colors()
    {
        return $this->hasMany(\App\Models\DealerColor::class, 'mark_id', 'id');
    }



    public function cars()
    {
        return $this->hasMany(\App\Models\Car::class, 'mark_id', 'id');
    }



    public function dnm()
    {
        return $this->hasOne(\App\Models\DnmModel::class, 'mark_id', 'id')->withDefault();
    }
}
