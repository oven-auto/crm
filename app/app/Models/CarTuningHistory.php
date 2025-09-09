<?php

namespace App\Models;

use App\Models\Interfaces\TuningPriceInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarTuningHistory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id')->withTrashed();
    }



    public static function add(TuningPriceInterface|Car $car, $message, $type = 0)
    {
        if($car::class == Car::class)
            $carId = $car->id;
        else
            $carId = $car->getCarId();

        self::create([
            'author_id' => auth()->user()->id,
            'car_id' => $carId,
            'comment' => $message,
            'type' => $type
        ]);
    }
}
