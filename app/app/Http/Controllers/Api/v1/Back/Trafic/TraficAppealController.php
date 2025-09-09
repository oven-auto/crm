<?php

namespace App\Http\Controllers\Api\v1\Back\Trafic;

use App\Http\Controllers\Controller;
use \App\Repositories\Trafic\AppealRepository;

class TraficAppealController extends Controller
{
    /**
     * Метод вернет справочник-список всех вариатов обращений, которые допустимы в структуре компании c id = $id
     * @param int $id id структуры компании
     * @param AppealRepository service Репазиторий обращений
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($id = 0, AppealRepository $service)
    {
        $data = $service->getAppealByCompanyStructure($id);

        return response()->json([
            'data' => $data,
            'success' => 1,
        ]);
    }
}
