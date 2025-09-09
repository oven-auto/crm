<?php

namespace App\Observers;

use App\Helpers\String\StringHelper;
use App\Models\CarTuningHistory;
use App\Models\CarTuningPrice;

class CarTuningObserver
{
    public function saved(CarTuningPrice $carGift)
    {
        $current = $carGift->price;
        $previos = $carGift->getOriginal('price') ?? 0;

        if($current == $previos)
            return;

        $operator = '>';
        
        $message = 'Изменение суммы тюнинга (';
        $message .= StringHelper::moneyMask($previos).$operator.StringHelper::moneyMask($current).').';
        
        CarTuningHistory::add($carGift, $message, 1);
    }



    public function deleting(CarTuningPrice $carGift)
    {
        $current = $carGift->price;
        $previos = 0;
        
        if($current == $previos)
            return;

        $operator = '>';
        
        $message = 'Изменение суммы тюнинга (';
        $message .= StringHelper::moneyMask($current).$operator.StringHelper::moneyMask($previos).').';
        
        CarTuningHistory::add($carGift, $message, 1);
    }
}
