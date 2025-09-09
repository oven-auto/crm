<?php

namespace App\Http\Controllers\Api\v1\Services\Select;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Facades\Cache;

class BrandSelectController extends Controller
{
    /**
     * @OA\Get(
     *  path="/services/html/select/brands",
     *  tags={"Списки"},
     *  operationId="getbrandsSelect",
     *  summary="Список всех брендов",
     *  description="Список всех брендов",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     * )
     */
    public function all()
    {
        $result = Cache::remember('list:brand', config('cache', 'period'), function() {
            return Brand::select('name', 'id')->get();
        });

        return response()->json([
            'data' => $result,
            'success' => 1
        ]);
    }



    /**
     * @OA\Get(
     *  path="/services/html/select/dealerbrands",
     *  tags={"Списки"},
     *  operationId="getdealerbrandsSelect",
     *  summary="Список дилерских брендов",
     *  description="Список дилерских брендов",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     * )
     */
    public function dealer()
    {
        $result = Cache::remember('list:dillerbrand', config('cache', 'period'), function() {
            return Brand::where('diller', 1)->select('name', 'id')->get();
        });
        
        return response()->json([
            'data' => $result,
            'success' => 1
        ]);
    }
}
