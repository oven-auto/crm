<?php

namespace App\Http\Controllers\Api\v1\Services\Select;

use App\Http\Controllers\Controller;
use App\Models\DeliveryTerm;
use App\Models\DetailingCost;
use App\Models\OrderType;
use Illuminate\Support\Facades\Cache;

class OrderController extends Controller
{
        /**
     * @OA\Get(
     *  path="/services/html/select/ordertypes",
     *  tags={"Списки"},
     *  operationId="getordertypesSelect",
     *  summary="Список типов заказа",
     *  description="Список типов заказа",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     * )
     */
    public function types()
    {
        $result = Cache::remember('order:types', config('cache', 'period'), function(){
            return OrderType::select('name','id')->get();
        });

        return response()->json([
            'data' => $result,
            'success' => 1,
        ]);
    }



    /**
     * @OA\Get(
     *  path="/services/html/select/deliveryterms",
     *  tags={"Списки"},
     *  operationId="getdeliverytermsSelect",
     *  summary="Список условия доставки",
     *  description="Список условия доставки",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     * )
     */
    public function deliveryterms()
    {
        $result = Cache::remember('order:deliveryterms', config('cache', 'period'), function(){
            return DeliveryTerm::select('name','id')->get();
        });

        return response()->json([
            'data' => $result,
            'success' => 1,
        ]);
    }



    /**
     * @OA\Get(
     *  path="/services/html/select/detailingcosts",
     *  tags={"Списки"},
     *  operationId="getdetailingcostsSelect",
     *  summary="Список типов детализации цены",
     *  description="Список типов детализации цены",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     * )
     */
    public function detailingcosts()
    {
        $result = Cache::remember('order:detailingcosts', config('cache', 'period'), function(){
            return DetailingCost::select('name','id')->get();
        });

        return response()->json([
            'data' => $result,
            'success' => 1,
        ]);
    }
}
