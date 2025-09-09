<?php

namespace App\Http\Controllers\Api\v1\Services\Select;

use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    /**
     * @OA\Get(
     *  path="/services/html/select/payments",
     *  tags={"Списки"},
     *  operationId="getPaymentList",
     *  summary="Список типов оплаты",
     *  description="Список типов оплаты",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     */
    public function index()
    {
        return response()->json([
            'data' => \App\Models\Payment::select(['id', 'name'])->get(),
            'success' => 1,
        ]);
    }
}
