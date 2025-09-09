<?php

namespace App\Http\Controllers\Api\v1\Services\Select;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mark;
use App\Models\MarkAlias;
use Illuminate\Support\Facades\Cache;

class MarkSelectController extends Controller
{
    /**
     * @OA\Get(
     *  path="/services/html/select/marks",
     *  tags={"Списки"},
     *  operationId="getmarksSelect",
     *  summary="Список моделей указаного бренда",
     *  description="Список моделей указаного бренда",
     *  @OA\RequestBody(
     *      required=true,
     *      description="Подставить бренд в запрос",
     *      @OA\JsonContent(
     *              
     *              @OA\Property(
     *                  property="brand_id", type="integer", description="Выбранный бренд", format="integer"
     *              ),
     *          ),
     *      
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      )
     *  )
     * )
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'brand_id' => 'required|numeric'
        ]);

        $result = Mark::withCount(['cars' => function($query){
            $query->leftJoin('car_status_types', 'car_status_types.car_id', 'cars.id')->where('car_status_types.status', '<>', 'saled');
        }])->where('brand_id', $validated['brand_id'])->where('diller_status', 1)->get();
        
        return response()->json([
            'success' => 1,
            'data' => $result->map(function($item){
                return [
                    'name' => $item->name,
                    'id' => $item->id,
                    'count' => $item->cars_count,
                ];
            }),
        ]);
    }



    public function all(Request $request, $result = [])
    {
        $validated = $request->validate([
            'brand_id' => 'sometimes|numeric'
        ]);

        if (!isset($validated['brand_id']))
            return response()->json([
                'success' => 1,
                'data' => $result,
            ]);

        $result = Mark::where('brand_id', $validated['brand_id'])->select('name', 'id')->get();

        return response()->json([
            'success' => 1,
            'data' => $result,
        ]);
    }



    /**
     * @OA\Get(
     *  path="/services/html/select/markaliases",
     *  tags={"Списки"},
     *  operationId="getAliasList",
     *  summary="Список синонимов моделей(ДНМ Лада)",
     *  description="Список синонимов моделей(ДНМ Лада)",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     */
    public function getaliases()
    {
        return response()->json([
            'data' => MarkAlias::orderBy('name')->where('status', 1)->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                ];
            }),
            'success' => 1,
        ]);
    }
}
