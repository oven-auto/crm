<?php 

namespace App\Services\Car\CarGeneral;

use App\Models\UsedCar;

Class UsedCarGeneral extends AbstractCarGeneral
{
    public function __construct(UsedCar $car)
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
        $this->typename = 'Автомобиль с пробегом';
        $this->year = $car->year;
        $this->type = 'used';
    }
}