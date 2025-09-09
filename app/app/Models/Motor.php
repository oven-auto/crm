<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Filterable;
use App\Models\Traits\Createable;

class Motor extends Model
{
    use HasFactory, Filterable, Createable;

    protected $guarded = [];

    public $timestamps = false;


    
    public function brand()
    {
        return $this->hasOne(\App\Models\Brand::class, 'id', 'brand_id')->withDefault();
    }



    public function toxic(){
        return $this->hasOne(\App\Models\MotorToxic::class, 'id', 'motor_toxic_id')->withDefault();
    }



    public function transmission()
    {
        return $this->hasOne(\App\Models\MotorTransmission::class, 'id', 'motor_transmission_id')->withDefault();
    }



    public function driver()
    {
        return $this->hasOne(\App\Models\MotorDriver::class, 'id', 'motor_driver_id')->withDefault();
    }



    public function type()
    {
        return $this->hasOne(\App\Models\MotorType::class, 'id', 'motor_type_id')->withDefault();
    }



    public function scopeFullData($query)
    {
        return $query->with(['transmission', 'type', 'brand', 'driver','toxic']);
    }
}
