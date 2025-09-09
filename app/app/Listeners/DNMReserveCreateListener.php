<?php

namespace App\Listeners;

use App\Classes\LadaDNM\DNMAppealService;
use App\Events\ReserveCreateEvent;
use App\Jobs\CreateDNMReserveJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DNMReserveCreateListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
       
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ReserveCreateEvent  $event
     * @return void
     */
    public function handle(ReserveCreateEvent $event)
    {
        CreateDNMReserveJob::dispatch($event->reserve);
    }
}
