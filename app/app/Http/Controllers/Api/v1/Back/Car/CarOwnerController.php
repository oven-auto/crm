<?php

namespace App\Http\Controllers\Api\v1\Back\Car;

use App\Http\Controllers\Controller;
use App\Http\Requests\Car\Owner\CarOwnerRequest;
use App\Http\Resources\Car\Owner\StoreOwnerResource;
use App\Models\Car;
use Illuminate\Http\Request;

class CarOwnerController extends Controller
{
    /**
     * @OA\Post(
     *      path="/cars/owners/{carId}",
     *      operationId="storeOwnerCar",
     *      tags={"Новый автомобиль"},
     *      summary="Списать автомобиль на клиента",
     *      description="Списать автомобиль на клиента (client_id)",
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      )
     * )
     */
    public function store(Car $car, CarOwnerRequest $request)
    {
        $car->saveOwner($request->validated());

        return new StoreOwnerResource($car->owner);
    }



    /**
     * @OA\Delete(
     *      path="/cars/owners/{carId}",
     *      operationId="deleteOwnerCar",
     *      tags={"Новый автомобиль"},
     *      summary="Удалить списание автомобиль на клиента",
     *      description="Удалить списание автомобиль на клиента",
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      )
     * )
     */
    public function destroy(Car $car, Request $request)
    {
        $car->owner()->delete();

        return response()->json([
            'success' => 1,
        ]);
    }
}
