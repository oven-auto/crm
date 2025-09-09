<?php

namespace App\Http\Controllers\Api\v1\Services\Select;

use App\Http\Controllers\Controller;
use App\Models\Tuning;
use Illuminate\Support\Facades\Cache;

class TuningController extends Controller
{
    /**
     * @OA\Get(
     *  path="/services/html/select/tunings",
     *  tags={"Списки"},
     *  operationId="getTuningSelect",
     *  summary="Список тюнинга",
     *  description="Список тюнинга",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     * )
     */
    public function devices()
    {
        $result = Cache::remember('list.devices', config('cache', 'period'), function(){
            return Tuning::select('name','id')->get();
        });

        return response()->json([
            'data' => $result,
            'success' => 1,
        ]);
    }
}
