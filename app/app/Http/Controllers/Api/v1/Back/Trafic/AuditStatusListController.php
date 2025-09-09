<?php

namespace App\Http\Controllers\Api\v1\Back\Trafic;

use App\Http\Controllers\Controller;

class AuditStatusListController extends Controller
{
    /**
     * Метод вернет справочник-список всех статусов стандартов аудита
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke()
    {
        return response()->json([
            'data' => \App\Models\AuditStandart::STATUSES,
            'success' => 1,
        ]);
    }
}
