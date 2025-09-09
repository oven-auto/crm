<?php

namespace App\Observers;

use App\Models\CarFixedPrice;

class FixedCarPrice
{
    public function saving(CarFixedPrice $carFP)
    {
        $carFP->total_price = $carFP->body_price + $carFP->packs_price + $carFP->equipments_price;
    }
}
