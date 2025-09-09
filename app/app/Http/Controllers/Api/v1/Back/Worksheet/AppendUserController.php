<?php

namespace App\Http\Controllers\Api\v1\Back\Worksheet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Worksheet\WorksheetAppendExecutorRequest;
use App\Http\Requests\Worksheet\WorksheetDeleteExecutorRequest;
use App\Services\Worksheet\WorksheetExecutorReportService;

class AppendUserController extends Controller
{
    protected $service;

    public function __construct(WorksheetExecutorReportService $service)
    {
        $this->service = $service;
    }



    /**
     * ДОБАВИТЬ ПОЛЬЗОВАТЕЛЯ В ИСПОЛНИТЕЛИ
     */
    public function append(WorksheetAppendExecutorRequest $request)
    {
        $this->service->attach($request->worksheet_id, $request->user_ids);

        return response()->json([
            'success' => 1,
            'message' => 'Пользователи добавлены',
        ]);
    }



    /**
     * УДАЛИТЬ ПОЛЬЗОВАТЕЛЯ ИЗ ИСПОЛНИТЕЛЕЙ
     */
    public function destroy(WorksheetDeleteExecutorRequest $request)
    {
        $this->service->detach($request->worksheet_id, $request->user_id);

        return response()->json([
            'success' => 1,
            'message' => 'Пользователь удален'
        ]);
    }
}
