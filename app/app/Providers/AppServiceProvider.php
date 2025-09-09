<?php

namespace App\Providers;

use App\Observers\ClientObserver;
use App\Observers\ServiceClientEventObserver;
use App\Observers\Worksheet\Modules\ReserveNewCarObserver;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \App\Models\Brand::observe(\App\Observers\BrandObserver::class);
        \App\Models\Mark::observe(\App\Observers\MarkObserver::class);
        \App\Models\Trafic::observe(\App\Observers\TraficObserver::class);
        \App\Models\Worksheet::observe(\App\Observers\WorksheetObserver::class);
        \App\Models\Role::observe(\App\Observers\RoleObserver::class);
        \App\Models\ClientEvent::observe(\App\Observers\ClientEventObserver::class);
        \App\Models\Car::observe(\App\Observers\CarObserver::class);
        \App\Models\User::observe(\App\Observers\UserObserver::class);
        \App\Models\Discount::observe(\App\Observers\DiscountObserver::class);

        \App\Models\CarGiftPrice::observe(\App\Observers\CarGiftObserver::class);
        \App\Models\CarTuningPrice::observe(\App\Observers\CarTuningObserver::class);
        \App\Models\CarPartPrice::observe(\App\Observers\CarPartObserver::class);
        \App\Models\WsmReserveNewCar::observe(ReserveNewCarObserver::class);
        \App\Models\WsmReservePayment::observe(\App\Observers\PaymentObserver::class);

        \App\Models\ClientPassport::observe(ServiceClientEventObserver::class);
        \App\Models\Worksheet\Service\WSMService::observe(ServiceClientEventObserver::class);
        
        Validator::excludeUnvalidatedArrayKeys();
    }
}
