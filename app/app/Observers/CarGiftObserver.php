<?php

namespace App\Observers;

use App\Helpers\String\StringHelper;
use App\Models\CarGiftPrice;
use App\Models\CarTuningHistory;

class CarGiftObserver
{
    public function saved(CarGiftPrice $carGift)
    {
        $current = $carGift->price;
        $previos = $carGift->getOriginal('price') ?? 0;

        if($current == $previos)
            return;

        $operator = '>';
        
        $message = 'Изменение суммы подарка (';
        $message .= StringHelper::moneyMask($previos).$operator.StringHelper::moneyMask($current).').';
        
        CarTuningHistory::add($carGift, $message);
    }



    public function deleted(CarGiftPrice $carGift)
    {
        $current = $carGift->price;
        $previos = 0;

        if($current == $previos)
            return;

        $operator = '>';
        
        $message = 'Изменение суммы подарка (';
        $message .= StringHelper::moneyMask($current).$operator.StringHelper::moneyMask($previos).').';
        
        CarTuningHistory::add($carGift, $message);
    }
}
