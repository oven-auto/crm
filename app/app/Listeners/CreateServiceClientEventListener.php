<?php

namespace App\Listeners;

use App\Events\CreateServiceClientEvent;
use App\Services\Client\ClientEvent\CreateEventFromClient;
use App\Services\Client\ClientEvent\CreateEventFromService;

class CreateServiceClientEventListener
{
    public function __construct()
    {
       
    }

    
    
    public function handle(CreateServiceClientEvent $event)
    {   
        $className = get_class($event->obj);
        
        $service = match($className) {
            \App\Models\Worksheet\Service\WSMService::class => CreateEventFromService::class,
            \App\Models\ClientPassport::class => CreateEventFromClient::class,
            default => 0,
        };
        
        $result = false;

        if($service)
            $result = $service::fromTemplate($event->obj);

        return $result;
    }
}
