<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarSalePriority extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function car()
    {
        return $this->hasOne(\App\Models\Car::class, 'id', 'car_id');
    }



    public function sale_priority()
    {
        return $this->hasOne(\App\Models\SalePriority::class, 'id', 'priority_id')->withDefault();
    }
}
