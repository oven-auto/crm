<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

use App\Events\TraficEvent;
use App\Listeners\SendTraficInfo;

use App\Events\ClientEvent;
use App\Listeners\SendClientInfo;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ClientEvent::class => [
            SendClientInfo::class,
        ],
        TraficEvent::class => [
            SendTraficInfo::class,
        ],
        \App\Events\ReserveCreateEvent::class => [
            \App\Listeners\DNMReserveCreateListener::class,
        ],
        \App\Events\DNMVisitEvent::class => [
            \App\Listeners\DNMEventListener::class,
        ],
        \App\Events\CreateServiceClientEvent::class => [
            \App\Listeners\CreateServiceClientEventListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
