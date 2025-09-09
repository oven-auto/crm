<?php

namespace App\Http\Controllers\Api\v1\Services\Select;

use App\Http\Controllers\Controller;
use App\Models\Marker;
use App\Models\TradeMarker;
use Illuminate\Support\Facades\Cache;

class MarkerSelectController extends Controller
{
    /**
     * @OA\Get(
     *  path="/services/html/select/markers",
     *  tags={"Списки"},
     *  operationId="getmarkersSelect",
     *  summary="Список контр-марок (марка логиста)",
     *  description="Список контр-марок (марка логиста)",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     * )
     */
    public function index()
    {
        $result = Cache::remember('list:marker', config('cache', 'period'), function(){
            return Marker::select('name', 'id')->get();
        });
        
        return response()->json([
            'data' => $result,
            'success' => 1
        ]);
    }



    /**
     * @OA\Get(
     *  path="/services/html/select/trademarkers",
     *  tags={"Списки"},
     *  operationId="gettrademarkersSelect",
     *  summary="Список товарных признаков",
     *  description="Список товарных признаков",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     * )
     */
    public function trademarker()
    {
        $result = Cache::remember('list:trademarker', config('cache', 'period'), function(){
            return TradeMarker::select('name', 'id')->get();
        });

        return response()->json([
            'data' => $result,
            'success' => 1,
        ]);
    }
}
