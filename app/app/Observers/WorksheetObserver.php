<?php

namespace App\Observers;

use App\Events\ClientCreateOrUpdateEvent;
use App\Events\WorksheetCreateEvent;

class WorksheetObserver
{
    public function created(\App\Models\Worksheet $worksheet)
    {
        $worksheet->trafic->process();

        $worksheet->executors()->attach($worksheet->author_id);

        //ClientCreateOrUpdateEvent::dispatch($worksheet->client);

        //WorksheetCreateEvent::dispatch($worksheet);
    }

    

    public function creating(\App\Models\Worksheet $worksheet)
    {

    }
}
