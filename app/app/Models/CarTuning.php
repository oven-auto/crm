<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CarTuning extends Pivot
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;



    public static function boot()
    {
        parent::boot();

        static::created(function($item){
            CarTuningHistory::add($item->car, message:'Добавлено – '.$item->tuning->name.'.');
        });

        static::deleted(function($item){
            CarTuningHistory::add($item->car, message:'Удалено – '.$item->tuning->name.'.');
        });
    }



    public function car()
    {
        return $this->hasOne(\App\Models\Car::class, 'id', 'car_id');
    }



    public function tuning()
    {
        return $this->hasOne(\App\Models\Tuning::class, 'id', 'tuning_id');
    }
}
