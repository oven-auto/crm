<?php

namespace App\Http\Controllers\Api\v1\Back\Car;

use App\Http\Controllers\Controller;
use App\Http\Resources\Car\CarCountResource;
use App\Repositories\Car\Car\CarRepository;
use Illuminate\Http\Request;

class CarCountController extends Controller
{
    public function __construct()
    {
        $this->middleware('carfilter')->only('count');
    }



    /**
     * @OA\Get(
     *      path="/cars/count",
     *      operationId="carsListCount",
     *      tags={"Новый автомобиль"},
     *      summary="Счетчик новых автомобилей",
     *      description="Счетчик новых автомобилейв",
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/CarFilter",
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function count(Request $request, CarRepository $repo)
    {
        $res = $repo->count($request->all());
        
        return new CarCountResource($res);
    }
}
