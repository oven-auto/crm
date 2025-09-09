<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Createable;

class MotorDriver extends Model
{
    use HasFactory,Createable;

    protected $fillable = ['name', 'acronym', 'driver_type_id'];

    public $timestamps = false;

    // public function type()
    // {
    // 	return $this->hasOne(\App\Models\DriverType::class, 'id', 'driver_type_id');
    // }
}
