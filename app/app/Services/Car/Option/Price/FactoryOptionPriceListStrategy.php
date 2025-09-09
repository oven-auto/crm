<?php

namespace App\Services\Car\Option\Price;

use App\Models\Car;

class FactoryOptionPriceListStrategy
{
    public static function getStrategy(array $arr)
    {
        $optionOnCar = null;

        if (isset($arr['car_id'])) {
            $car = Car::find($arr['car_id']);
            $optionOnCar = $car->options->contains('id', $arr['option_id']);
        }
        if ($optionOnCar)
            return new StrategyCarOptionPriceList(carId: $arr['car_id'], optionId: $arr['option_id']);
        elseif (isset($arr['option_id']))
            return new StrategyOptionPriceList(optionId: $arr['option_id']);
        throw new \Exception('Не могу взять список цен, параметры не подходят');
    }
}
