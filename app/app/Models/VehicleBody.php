<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleBody extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;



    public function vehicletype()
    {
        return $this->hasOne(\App\Models\VehicleType::class, 'id', 'vehicle_id');
    }



    public function bodywork()
    {
        return $this->hasOne(\App\Models\BodyWork::class, 'id', 'bodywork_id');
    }
}
