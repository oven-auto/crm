<?php

namespace App\Http\Controllers\Api\v1\Services\Select;

use App\Http\Controllers\Controller;
use App\Models\ReasonRefusal;

class ReasonRefusalController extends Controller
{   
    /**
     * @OA\Get(
     *  path="/services/html/select/reasons",
     *  tags={"Списки"},
     *  operationId="getReasonList",
     *  summary="Список причин отказа от РЛ (ДНМ Лада)",
     *  description="Список причин отказа от РЛ (ДНМ Лада)",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     */
    public function index()
    {
        return response()->json([
            'data' => ReasonRefusal::get()->map(function ($item) {
                return [
                    'name' => $item->name,
                    'id' => $item->id
                ];
            }),
            'success' => 1,
        ]);
    }
}
