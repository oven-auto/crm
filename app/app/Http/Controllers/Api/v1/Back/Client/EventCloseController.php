<?php

namespace App\Http\Controllers\Api\v1\Back\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Client\EventCloseService;
use \App\Http\Resources\Client\EventSaveResource;

class EventCloseController extends Controller
{
    private $service;

    public function __construct(EventCloseService $service)
    {
        $this->service = $service;
    }



    /**
     * Закрыть коммуникацию
     * @param Request $request [event_status_id]
     * @return EventSaveResource
     */
    public function close(Request $request) : EventSaveResource
    {
        $eventStatus = $this->service->close($request->event_status_id);

        return (new EventSaveResource($eventStatus))
            ->additional(['message' => $eventStatus->lastComment->text]);
    }



    /**
     * Вернуть событие в работу
     */
    public function resume(Request $request)
    {
        $eventStatus = $this->service->resume($request->get('event_status_id'));

        return (new EventSaveResource($eventStatus))
            ->additional(['message' => $eventStatus->lastComment->text]);
    }
}
