<?php

namespace App\Services\Car\CarGeneral;

use App\Models\Car;

Class NewCarGeneral extends AbstractCarGeneral
{
    public function __construct(Car $car)
    {
        $this->id = $car->id;
        $this->vin = $car->vin;
        $this->brand = $car->brand->name;
        $this->model = $car->mark->name;
        $this->bodywork = $car->complectation->bodywork->name;
        $this->vehicle = $car->complectation->vehicle->name;
        $this->size = $car->complectation->motor->size;
        $this->power = $car->complectation->motor->power;
        $this->transmission = $car->complectation->motor->transmission->acronym;
        $this->driver = $car->complectation->motor->driver->acronym;
        $this->typename = 'Новый автомобиль';
        $this->year = $car->year;
        $this->type = 'new';
    }
}