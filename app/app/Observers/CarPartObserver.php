<?php

namespace App\Observers;

use App\Helpers\String\StringHelper;
use App\Models\CarPartPrice;
use App\Models\CarTuningHistory;

class CarPartObserver
{
    public function saved(CarPartPrice $carGift)
    {
        $current = $carGift->price;
        $previos = $carGift->getOriginal('price') ?? 0;

        if($current == $previos)
            return;

        $operator = '>';
        
        $message = 'Изменение закупа з/ч (';
        $message .= StringHelper::moneyMask($previos).$operator.StringHelper::moneyMask($current).').';
        
        CarTuningHistory::add($carGift, $message);
    }



    public function deleted(CarPartPrice $carGift)
    {
        $current = $carGift->price;
        $previos = 0;

        if($current == $previos)
            return;

        $operator = '>';
        
        $message = 'Изменение закупа з/ч (';
        $message .= StringHelper::moneyMask($current).$operator.StringHelper::moneyMask($previos).').';
        
        CarTuningHistory::add($carGift, $message);
    }
}
