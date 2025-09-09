<?php

namespace App\Observers;

use App\Models\Client;
use App\Events\ClientCreateOrUpdateEvent;

class ClientObserver
{
    public function saved(Client $client)
    {
        
    }
}
