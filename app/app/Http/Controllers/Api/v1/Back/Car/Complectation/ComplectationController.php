<?php

namespace App\Http\Controllers\Api\v1\Back\Car\Complectation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Car\Complectation\ComplectationGetRequest;
use App\Http\Requests\Car\Complectation\ComplectationRequest;
use App\Http\Requests\Car\Complectation\ComplectationSearchRequest;
use App\Http\Resources\Car\Complectation\ComplectationCollection;
use App\Http\Resources\Car\Complectation\ComplectationItemResource;
use App\Http\Resources\Car\Complectation\ComplectationSearchResource;
use App\Models\Complectation;
use App\Repositories\Car\Complectation\ComplectationRepository;

class ComplectationController extends Controller
{
    public function __construct(
        private ComplectationRepository $repo,
        public $subject = 'Комплектация',
        public $genus = 'female'
    )
    {
        $this->middleware('notice.message')->only(['store', 'update', 'delete', 'restore']);
    }



    /**
     * @OA\Get(
     *  path="/cars/complectations",
     *  operationId="complectation_list",
     *  tags={"Комплектации новых автомобилей"},
     *  summary="Список комплектаций",
     *  description="complectations",
     *  @OA\RequestBody(
     *     @OA\JsonContent(
     *          type="object",
     *          ref="#/components/schemas/ComplectationFilter",
     *     )
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function index(ComplectationGetRequest $request)
    {
        $complectations = $this->repo->get($request->validated());

        return new ComplectationCollection($complectations);
    }


    
    /**
     * @OA\Post(
     *  path="/cars/complectations/search",
     *  operationId="complectationSearch",
     *  tags={"Комплектации новых автомобилей"},
     *  summary="Поиск комплектаций по коду",
     *  description="Поиск комплектаций по коду",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function search(ComplectationSearchRequest $request) : ComplectationSearchResource | \Illuminate\Http\JsonResponse
    {
        $complectation = $this->repo->searchByCode($request->validated());

        if($complectation)
            return new ComplectationSearchResource($complectation);

        return response()->json(['success' => 0, 'message' => 'Совпадений не найдено'], 404);
    }



    /**
     * @OA\Post(
     *  path="/cars/complectations",
     *  operationId="complectationStore",
     *  tags={"Комплектации новых автомобилей"},
     *  summary="Создать комплектацию",
     *  description="Создать комплектацию(
     *      code,name, mark_id, vehicle_type_id,  body_work_id, factory_id, motor_driver_id,
     *      motor_transmission_id, motor_type_id, power, size, ?file, brand_id, alias_id,
     *  )",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function store(ComplectationRequest $request) : ComplectationItemResource
    {
        $complectation = $this->repo->create($request->validated());

        return (new ComplectationItemResource($complectation));
    }



    /**
     * @OA\Post(
     *  path="/cars/complectations/{complectationId}",
     *  operationId="complectationUpdate",
     *  tags={"Комплектации новых автомобилей"},
     *  summary="Изменить комплектацию",
     *  description="Изменить комплектацию (
     *      code,name, mark_id, vehicle_type_id,  body_work_id, factory_id, motor_driver_id,
     *      motor_transmission_id, motor_type_id, power, size, ?file, brand_id, alias_id,
     *  )",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function update(Complectation $complectation, ComplectationRequest $request) : ComplectationItemResource
    {
        $this->repo->update($complectation, $request->validated());

        return (new ComplectationItemResource($complectation));
    }



    /**
     * @OA\Get(
     *  path="/cars/complectations/{complectationId}",
     *  operationId="complectationShow",
     *  tags={"Комплектации новых автомобилей"},
     *  summary="Открыть комплектацию",
     *  description="Открыть комплектацию",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function show(Complectation $complectation) : ComplectationItemResource
    {
        return new ComplectationItemResource($complectation);
    }



    /**
     * @OA\Delete(
     *  path="/cars/complectations/{complectationId}",
     *  operationId="complectationDelete",
     *  tags={"Комплектации новых автомобилей"},
     *  summary="Удалить комплектацию",
     *  description="Удалить комплектацию",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function delete(Complectation $complectation) : \Illuminate\Http\JsonResponse
    {
        $this->repo->delete($complectation);

        return response()->json(['success' => 1]);
    }



    /**
     * @OA\Patch(
     *  path="/cars/complectations/{complectationId}/restore",
     *  operationId="complectationRestore",
     *  tags={"Комплектации новых автомобилей"},
     *  summary="Востановить комплектацию",
     *  description="Востановить комплектацию",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function restore(Complectation $complectation) : ComplectationItemResource
    {
        $this->repo->restore($complectation);

        return (new ComplectationItemResource($complectation));
    }
}
