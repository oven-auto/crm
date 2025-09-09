<?php

namespace App\Observers;

use App\Events\CreateServiceClientEvent;
use App\Models\Interfaces\EventInterface;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class ServiceClientEventObserver implements ShouldHandleEventsAfterCommit
{
    public function saved(EventInterface $obj)
    {
        CreateServiceClientEvent::dispatch($obj);
    }
}
