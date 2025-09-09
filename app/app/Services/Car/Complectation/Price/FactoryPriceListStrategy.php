<?php

namespace App\Services\Car\Complectation\Price;

class FactoryPriceListStrategy
{
    public static function getStrategy(array $arr)
    {
        if (isset($arr['car_id']))
            return new CarPriceListStrategy($arr['car_id']);
        elseif (isset($arr['complectation_id']))
            return new ComplectationPriceListStrategy($arr['complectation_id']);
        throw new \Exception('Не могу взять список цен, параметры не подходят');
    }
}
