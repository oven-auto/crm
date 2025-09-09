<?php

namespace App\Http\Controllers\Api\v1\Services\Select;

use App\Http\Controllers\Controller;

class CollectorController extends Controller
{
    /**
     * @OA\Get(
     *  path="/services/html/select/collectors",
     *  tags={"Списки"},
     *  operationId="getCarCollectors",
     *  summary="Список залогодателей (держатель залога)",
     *  description="Список залогодателей (держатель залога)",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     */
    public function index()
    {
        return response()->json([
            'data' => \App\Models\Collector::select(['id', 'name'])->get(),
            'success' => 1,
        ]);
    }
}
