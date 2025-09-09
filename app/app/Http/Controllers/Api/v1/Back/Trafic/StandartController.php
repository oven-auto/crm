<?php

namespace App\Http\Controllers\Api\v1\Back\Trafic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuditStandart;
use App\Http\Resources\Trafic\TraficSexCollection;

class StandartController extends Controller
{
    /**
     * Метод вернет справочник-список всех типов(сценариев) стандартов аудита
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke()
    {
        $standarts = AuditStandart::select('id','name','target')->where('trafic',1)->get();
        return response()->json([
            'data' => $standarts,
            'success' => 1
        ]);
    }
}
