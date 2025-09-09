<?php

namespace App\Classes\Car\CarPriority;

use App\Models\Car;

Class CarPriority
{
    public static function make(Car $car)
    {
        return PrioritySetter::make($car);
    }
}