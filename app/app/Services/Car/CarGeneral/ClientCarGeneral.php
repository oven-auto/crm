<?php

namespace App\Services\Car\CarGeneral;

use App\Models\ClientCar;

Class ClientCarGeneral extends AbstractCarGeneral
{
    public function __construct(ClientCar $car)
    {
        $this->id = $car->id;
        $this->vin = $car->vin;
        $this->brand = $car->brand->name;
        $this->model = $car->mark->name;
        $this->bodywork = $car->bodywork->name;
        $this->vehicle = $car->vehicle->name;
        $this->size = $car->motor_size;
        $this->power = $car->motor_power;
        $this->transmission = $car->transmission->acronym;
        $this->driver = $car->driver->acronym;
        $this->typename = 'Клиентский автомобиль';
        $this->year = $car->year;
        $this->type = 'client';
    }
}