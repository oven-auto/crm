<?php

namespace App\Http\Controllers\Api\v1\Services\Select;

use App\Http\Controllers\Controller;

class CarLogisticStatusController extends Controller
{
    /**
     * @OA\Get(
     *  path="/services/html/select/car_states",
     *  tags={"Списки"},
     *  operationId="getCarStatusLogTypes",
     *  summary="Список типов этапов поставки нового авто",
     *  description="Список типов поставки нового авто",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     */
    public function index()
    {
        return response()->json([
            'data' => \App\Models\CarState::where('sort', '>', 0)->orderBy('sort')->pluck('status', 'description'),
            'success' => 1
        ]);
    }
}
