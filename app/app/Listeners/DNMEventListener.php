<?php

namespace App\Listeners;

use App\Classes\LadaDNM\DNMEvent;
use App\Events\DNMVisitEvent;
use App\Jobs\CreateDNMEventJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DNMEventListener
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
     * @param  \App\Events\DNMVisitEvent  $event
     * @return void
     */
    public function handle(DNMVisitEvent $event)
    {
        CreateDNMEventJob::dispatch($event->reserve, $event->eventType);
    }
}
