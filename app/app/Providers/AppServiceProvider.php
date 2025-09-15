<?php

namespace App\Providers;

use App\Models\ClientPassport;
use App\Models\Worksheet\Service\WSMService;
use App\Models\WSMCredit;
use App\Models\WSMCreditAward;
use App\Models\WSMCreditCalculation;
use App\Models\WSMCreditContract;
use App\Observers\ServiceClientEventObserver;
use App\Observers\Worksheet\Modules\ReserveNewCarObserver;
use App\Observers\WSMCreditObserver;
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

        // ClientPassport::observe(ServiceClientEventObserver::class);
        // WSMService::observe(ServiceClientEventObserver::class);

        collect([ClientPassport::class, WSMService::class])->each(function($item){
            $item::observe(ServiceClientEventObserver::class);
        });
        
        // collect([
        //     WSMCreditContract::class, 
        //     WSMCredit::class, 
        //     WSMCreditAward::class, 
        //     WSMCreditCalculation::class
        // ])->each(function($item){
        //     $item::observe(WSMCreditObserver::class);
        // });
        
        Validator::excludeUnvalidatedArrayKeys();
    }
}
