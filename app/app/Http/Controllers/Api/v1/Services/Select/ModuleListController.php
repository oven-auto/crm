<?php

namespace App\Http\Controllers\Api\v1\Services\Select;

use App\Http\Controllers\Controller;

class ModuleListController extends Controller
{
    /**
     * @OA\Get(
     *  path="/services/html/select/modules",
     *  tags={"Списки"},
     *  operationId="getModuleList",
     *  summary="Список типов бизнес модулей РЛ",
     *  description="Список типов бизнес модулей РЛ",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     */
    public function index()
    {
        return response()->json([
            'data' => \App\Models\Modul::get()->toArray(),
            'success' => 1
        ]);
    }
}
