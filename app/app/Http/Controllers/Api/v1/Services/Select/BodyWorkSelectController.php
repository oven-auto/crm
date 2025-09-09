<?php

namespace App\Http\Controllers\Api\v1\Services\Select;

use App\Http\Controllers\Controller;
use App\Models\BodyWork;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BodyWorkSelectController extends Controller
{
        /**
     * @OA\Get(
     *  path="/services/html/select/bodyworks",
     *  tags={"Списки"},
     *  operationId="getBodyTypeSelect",
     *  summary="Список типов кузова",
     *  description="Список типов кузова",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     */
    public function index(Request $request)
    {
        $query = BodyWork::query()->select('name', 'id');

        if ($request->has('vehicle_id'))
            $query->leftJoin('vehicle_bodies', 'vehicle_bodies.bodywork_id', 'body_works.id')
                ->where('vehicle_bodies.vehicle_id', $request->vehicle_id);

        $result = $query->orderBy('body_works.name')->get();

        return response()->json([
            'data' => $result,
            'success' => 1
        ]);
    }



    /**
     * @OA\Get(
     *  path="/services/html/select/vehicletypes",
     *  tags={"Списки"},
     *  operationId="getVehicleTypeSelect",
     *  summary="Список типов ТС",
     *  description="Список типов ТС",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     */
    public function vehicletypes()
    {
        $result = VehicleType::select('name', 'id')->get();

        return response()->json([
            'data' => $result,
            'success' => 1,
        ]);
    }



    /**
     * @OA\Get(
     *  path="/services/html/select/bodyacronyms",
     *  tags={"Списки"},
     *  operationId="getAcronymTypeSelect",
     *  summary="Список акронимов типа кузова",
     *  description="Список акронимов типа кузова",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     */
    public function acronym()
    {
        $result = BodyWork::select('acronym', 'name')->where('main', 1)->get();

        return response()->json([
            'data' => $result->map(function ($item) {
                return [
                    'name' => $item->name,
                    'acronym' => $item->acronym,
                ];
            }),
            'success' => 1
        ]);
    }
}
