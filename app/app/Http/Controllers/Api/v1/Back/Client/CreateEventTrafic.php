<?php

namespace App\Http\Controllers\Api\v1\Back\Client;

use App\Http\Controllers\Controller;
use App\Models\ClientEventStatus;
use App\Services\Client\EventTraficService;
use Illuminate\Http\Request;

class CreateEventTrafic extends Controller
{
    /**
     * Создать трафик из коммуникации
     * @param Request $request [event_status_id => id]
     */
    public function __invoke(Request $request, EventTraficService $service)
    {
        $eventStatus = ClientEventStatus::findOrFail($request->get('event_status_id'));

        $traficId = $service->createTrafic($eventStatus);

        return (new \App\Http\Resources\Client\EventSaveResource($eventStatus))
            ->additional([
                'data' => [
                    'trafic_id' => $traficId
                ],
                'message' => $eventStatus->lastComment->text,
            ]);
    }
}
