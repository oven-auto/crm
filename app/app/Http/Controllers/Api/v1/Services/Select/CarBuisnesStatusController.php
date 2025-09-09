<?php

namespace App\Http\Controllers\Api\v1\Services\Select;

use App\Http\Controllers\Controller;
use App\Models\Worksheet\Service\WSMServiceCar;

class CarBuisnesStatusController extends Controller
{
    /**
     * @OA\Get(
     *  path="/services/html/select/car_status_types",
     *  tags={"Списки"},
     *  operationId="getCarStatusBuisnesTypes",
     *  summary="Список типов статуса нового авто",
     *  description="Список типов статуса нового авто",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     */
    public function index()
    {
        return response()->json([
            'data' => \App\Models\CarStatusType::STATES,
            'success' => 1
        ]);
    }



    /**
     * @OA\Get(
     *  path="/services/html/select/report_types",
     *  tags={"Списки"},
     *  operationId="getCarSReportTypes",
     *  summary="Список типов рапорта",
     *  description="Список типов рапорта",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     */
    public function report()
    {
        return response()->json([
            'data' => \App\Models\Car::REPORT_STATUSES,
            'success' => 1
        ]);
    }



        /**
     * @OA\Get(
     *  path="/services/html/select/cartypes",
     *  tags={"Списки"},
     *  operationId="getCarSTypes",
     *  summary="Список типов авто для финуслуг",
     *  description="Список типов авто для финуслуг",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     */
    public function type()
    {
        return response()->json([
            'data' => WSMServiceCar::getArrayType(),
            'success' => 1
        ]);
    }
}
