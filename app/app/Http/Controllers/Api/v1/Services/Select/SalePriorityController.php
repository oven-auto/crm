<?php

namespace App\Http\Controllers\Api\v1\Services\Select;

use App\Http\Controllers\Controller;
use App\Models\SalePriority;
use Illuminate\Http\Request;

class SalePriorityController extends Controller
{
    /**
     * @OA\Get(
     *  path="/services/html/select/salepriorities",
     *  tags={"Списки"},
     *  operationId="getSalePriorityList",
     *  summary="Список приоритетов продажи",
     *  description="Список приоритетов продажи",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     */
    public function index()
    {
        return response()->json([
            'data' => SalePriority::get(),
            'success' => 1,
        ]);
    }
}
