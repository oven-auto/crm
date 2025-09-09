<?php

namespace App\Http\Controllers\Api\v1\Back\Credit;

use App\Http\Controllers\Controller;
use App\Models\CreditStatus;

class StatusController extends Controller
{
    /**
     * @OA\Get(
     *      path="/services/html/select/creditstatuses",
     *      operationId="getStatusCredit",
     *      tags={"Списки"},
     *      summary="Статусы кредита",
     *      description="Статусы кредита",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function index()
    {
        return response()->json([
            'data' => CreditStatus::get(),
            'success' => 1,
        ]);
    }
}
