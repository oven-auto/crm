<?php

namespace App\Observers;

use App\Classes\Notice\Notice;
use App\Models\Car;
use App\Models\CarState;
use App\Services\Car\CarLogisticStateService;

class CarObserver
{
    public function saved(Car $car)
    {
        // $stateService = new CarLogisticStateService($car);

        // $lastState = $stateService->getLastLogisticState();

        // if (!$lastState)
        //     return;

        // $carState = CarState::query()->where('logistic_system_name', $lastState->logistic_system_name)->first();

        // $car->saveCarStatus($carState);
    }



    public function created(Car $car)
    {
       
    }



    public function updated(Car $car)
    {
       
    }
}
