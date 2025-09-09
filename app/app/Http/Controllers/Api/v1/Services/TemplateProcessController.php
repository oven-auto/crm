<?php

namespace App\Http\Controllers\Api\v1\Services;

use App\Http\Controllers\Controller;
use App\Models\ClientEventTemplateProcess;
use Illuminate\Http\Request;

class TemplateProcessController extends Controller
{
    /**
     * @OA\Get(
     *  path="/services/html/select/templateprocess",
     *  tags={"Списки"},
     *  operationId="getBodytemplateprocess",
     *  summary="Список процессов шаблонов",
     *  description="Список процессов шаблонов",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     */
    public function index()
    {
        return response()->json([
            'data' => ClientEventTemplateProcess::get(),
            'success' => 1,
        ]);
    }
}
