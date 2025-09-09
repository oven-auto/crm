<?php

namespace App\Services\UsedCar;

use App\Models\UsedCar;
use App\Models\WSMRedemptionCar;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

Class UsedCarService
{
    public function createUsedCarFromRedmption(WSMRedemptionCar $redemption)
    {
        $carData = $redemption->client_car->getAttributes();
        
        $carData['agent_id'] = $redemption->client_id;
        $carData['author_id'] = Auth::id();
        $carData['wsm_redemption_car_id'] = $redemption->id;
        $carData['purchase_price'] = $redemption->last_purchase->price;

        UsedCar::create(Arr::except($carData, ['created_at', 'updated_at', 'client_id', 'actual', 'editor_id']));

        $redemption->client_car->setNonActual();
    }
}