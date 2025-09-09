<?php

namespace App\Http\Controllers\Api\v1\Back\Car;

use App\Http\Controllers\Controller;
use App\Http\Resources\Car\Car\CarItemResource;
use App\Models\Car;
use App\Repositories\Car\Car\CarRepository;

class CarCloneController extends Controller
{
    private $repo;

    public function __construct(CarRepository $repo)
    {
        $this->repo = $repo;
    }


    /**
     * @OA\Get(
     *      path="/cars/clone/{id}",
     *      operationId="getCarCloneData",
     *      tags={"Новый автомобиль"},
     *      summary="Получить данные для клона нового автомобиля",
     *      description="Получить данные для клона нового автомобиля",
     *      @OA\Parameter(
     *          name="id",
     *          description="Идентификатор автомобиля",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *      ),
     * )
     */
    public function clone(Car $car)
    {
        $clone = $this->repo->clone($car);

        return (new CarItemResource($clone));
    }
}
