<?php

namespace App\Observers\Worksheet\Modules;

use App\Models\WSMRedemptionCar;

class RedemptionCarObserver
{
    public const CLOSED = [3,2];

    public function saving(WSMRedemptionCar $redemption)
    {

    }
}
