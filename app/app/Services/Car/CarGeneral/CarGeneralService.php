<?php

namespace App\Services\Car\CarGeneral;

use App\Models\Interfaces\CarableInterface;

Class CarGeneralService
{
    public static function make(CarableInterface|null $car) : AbstractCarGeneral|null
    {
        return match($car::class) {
            'App\Models\Car' => new NewCarGeneral($car),
            'App\Models\ClientCar' => new ClientCarGeneral($car),
            'App\Models\UsedCar' => new UsedCarGeneral($car),
            default => null,
        };
    }
}