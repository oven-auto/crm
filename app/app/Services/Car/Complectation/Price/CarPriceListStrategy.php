<?php

namespace App\Services\Car\Complectation\Price;

use App\Models\Car;

class CarPriceListStrategy implements PriceListStrategyInterface
{
    private $car;
    public $complectation;

    public function __construct(int $carId)
    {
        $this->car = Car::withTrashed()->findOrfail($carId);
        $this->complectation = $this->car->complectation;
    }



    public function getCurrentPrice()
    {
        return $this->car->getComplectationCurrentPrice();
    }



    public function getComplectation()
    {
        return $this->complectation;
    }



    public function getPriceList()
    {
        return $this->complectation->prices;
    }
}
