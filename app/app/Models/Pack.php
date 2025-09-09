<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Filterable;
use App\Models\Interfaces\HasPriceInterface;
use App\Models\Traits\PriceChangeable;
use App\Models\Traits\Createable;

class Pack extends Model implements HasPriceInterface
{
    use HasFactory;
    use Filterable;
    use PriceChangeable;
    use Createable;

    protected $guarded = [];

    public function brand()
    {
        return $this->hasOne(\App\Models\Brand::class, 'id', 'brand_id')->withDefault();
    }

    // public function devices()
    // {
    //     return $this->belongsToMany(\App\Models\Device::class, 'pack_devices');
    // }

    public function marks()
    {
        return $this->belongsToMany(\App\Models\Mark::class, 'pack_marks');
    }

    public function scopeFullData($query)
    {
        return $query->with(['brand', 'devices']);
    }

    public function scopeByCarId($query, $car_id)
    {
        return $query->leftJoin('car_packs','car_packs.pack_id','=','packs.id')
            ->where('car_packs.car_id', $car_id);
    }

    public function scopeByComplectId($query, $complect_id)
    {
        return $query->leftJoin('complectation_packs','complectation_packs.pack_id','=','packs.id')
            ->where('complectation_packs.complectation_id', $complect_id);
    }
}
