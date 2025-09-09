<?php

namespace App\Http\Controllers\Api\v1\Back\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\Client\Event\Executor\ClientEventExecutorCollection;
use App\Models\ClientEventStatus;
use Illuminate\Http\Request;

class EventExecutorController extends Controller
{
    protected $service;

    public function __construct(\App\Services\Client\EventExecutorReporterService $serv)
    {
        $this->service = $serv;
    }



    /**
     * attach executors to event
     */
    public function store(ClientEventStatus $clientEventStatus, Request $request)
    {
        $executors = $this->service->append($clientEventStatus, $request->executor_ids);

        return (new ClientEventExecutorCollection($executors))
            ->additional(['message' => 'Данные добавлены',]);
    }



    /**
     * dettach executor from event
     */
    public function delete(ClientEventStatus $clientEventStatus, Request $request)
    {
        $deletedUsers = $this->service->detach($clientEventStatus, $request->executor_id);

        return (new ClientEventExecutorCollection($deletedUsers))
            ->additional(['message' => 'Исполнитель удален',]);
    }
}
