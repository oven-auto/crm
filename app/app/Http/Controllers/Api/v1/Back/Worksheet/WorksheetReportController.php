<?php

namespace App\Http\Controllers\Api\v1\Back\Worksheet;

use App\Http\Controllers\Controller;
use App\Services\Worksheet\WorksheetExecutorReportService;
use Illuminate\Http\Request;

class WorksheetReportController extends Controller
{
    public $service;

    public function __construct(WorksheetExecutorReportService $service)
    {
        $this->service = $service;
    }



    /**
     * ОТЧИТАТЬСЯ ЗА РЛ
     * @param Request $request [worksheet_id => int]
     */
    public function report(Request $request)
    {
        $this->service->report($request->worksheet_id);

        return response()->json([
            'message' => 'Вы отчитались',
            'success' => 1,
        ]);
    }



    /**
     * ОТМЕНИТЬ ОТЧЕТ ЗА РАБОЧИЙ ЛИСТ
     * @param Request $request [worksheet_id = int, user_id = int]
     */
    public function deport(Request $request)
    {
        $this->service->deport($request->worksheet_id, $request->user_id);

        return response()->json([
            'message' => 'Отчет пользователя отменен',
            'success' => 1,
        ]);
    }
}
