<?php

namespace App\Http\Controllers\Api\v1\Services\Select;

use App\Http\Controllers\Controller;
use App\Models\DealType;
use Illuminate\Http\Request;

class DealTypeController extends Controller
{
    /**
     * @OA\Get(
     *  path="/services/html/select/dealtypes",
     *  tags={"Списки"},
     *  operationId="getDealTypeSelect",
     *  summary="Список типов сделки для плана оплаты",
     *  description="Список типов сделки для плана оплаты",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     */
    public function index()
    {
        //TODO Проверить контроллер получения списка типов оплаты
        return response()->json([
            'data'      => DealType::get(),
            'success'   => 1
        ]);
    }
}
