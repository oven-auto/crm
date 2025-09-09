<?php

namespace App\Http\Controllers\Api\v1\Back\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\Client\Event\Reporter\ClientEventReporterResource;
use App\Models\ClientEventStatus;
use Illuminate\Http\Request;

class EventReportController extends Controller
{
    protected $service;

    public function __construct(\App\Services\Client\EventExecutorReporterService $serv)
    {
        $this->service = $serv;
    }



    /**
     * ОТЧИТАТЬСЯ
     */
    public function report(Request $request)
    {
        $user = $this->service->report(ClientEventStatus::findOrFail($request->get('id')), auth()->user()->id);

        return (new ClientEventReporterResource($user))
            ->additional(['message' => 'Вы успешно отчитались', 'success' => 1]);
    }



    /**
     * ОТМЕНИТЬ ОТЧЕТ
     */
    public function deport(Request $request)
    {
        $user = $this->service->deport(ClientEventStatus::find($request->id), $request->reporter_id);

        return (new ClientEventReporterResource($user))
            ->additional(['message' => 'Отчет отменен', 'success' => 1]);
    }
}
