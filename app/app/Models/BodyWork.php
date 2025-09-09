<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Createable;

class BodyWork extends Model
{
    use HasFactory, Createable;

    protected $fillable = ['name', 'sort', 'acronym'];

    public $timestamps = false;

    public function vehiclebodies()
    {
        return $this->belongsToMany(
            \App\Models\VehicleType::class,
            'vehicle_bodies',

            'bodywork_id',
            'vehicle_id',
        );
    }



    public function getVehicleAttribute()
    {
        return $this->vehiclebodies->first() ?? new VehicleType();
    }
}
