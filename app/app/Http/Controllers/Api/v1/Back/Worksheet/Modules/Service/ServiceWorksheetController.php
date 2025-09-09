<?php

namespace App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\Worksheet\Service\WorksheetServiceCreateRequest;
use App\Http\Requests\Worksheet\Service\WorksheetServiceRequest;
use App\Http\Resources\Default\SuccessResource;
use App\Http\Resources\Worksheet\Service\ServiceCollection;
use App\Http\Resources\Worksheet\Service\ServiceItemResource;
use App\Repositories\Worksheet\Modules\Service\ServiceWorksheetRepository;

//use App\Services\Worksheet\Service\Actuality\ActualityService;

class ServiceWorksheetController extends Controller
{
    public function __construct(
        private ServiceWorksheetRepository $repo
    )
    {
        
    }



    /**
     * @OA\Get(
     *      path="/worksheet/modules/services",
     *      operationId="worksheetServiceList",
     *      tags={"МОДУЛЬ РЛ: Финансовые сервисы"},
     *      summary="Список для рабочего листа (worksheet_id = int) ",
     *      description="Список для рабочего листа ",
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function index(WorksheetServiceRequest $request)
    {
        $services = $this->repo->getAll($request->validated());

        return new ServiceCollection($services);
    }



    /**
     * @OA\Post(
     *      path="/worksheet/modules/services",
     *      operationId="worksheetServiceCreate",
     *      tags={"МОДУЛЬ РЛ: Финансовые сервисы"},
     *      summary="Создать ",
     *      description="Создать ",
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/WorksheetServiceCreateRequest",
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function store(WorksheetServiceCreateRequest $request)
    {   
        $service = $this->repo->create($request->getDTO());

        return new ServiceItemResource($service);
    }



    /**
     * @OA\Patch(
     *      path="/worksheet/modules/services/{id}",
     *      operationId="worksheetServiceUpdate",
     *      tags={"МОДУЛЬ РЛ: Финансовые сервисы"},
     *      summary="Изменить ",
     *      description="Изменить ",
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/WorksheetServiceCreateRequest",
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function update(int $id, WorksheetServiceCreateRequest $request)
    {
        $service = $this->repo->update($id, $request->getDTO());
        
        return new ServiceItemResource($service);
    }



    /**
     * @OA\Get(
     *      path="/worksheet/modules/services/{id}",
     *      operationId="worksheetServiceShow",
     *      tags={"МОДУЛЬ РЛ: Финансовые сервисы"},
     *      summary="Открыть ",
     *      description="Открыть ",
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function show(int $id)
    {
        $service = $this->repo->getById($id);

        return new ServiceItemResource($service);
    }



    /**
     * @OA\Delete(
     *      path="/worksheet/modules/services/{id}",
     *      operationId="worksheetServiceDestroy",
     *      tags={"МОДУЛЬ РЛ: Финансовые сервисы"},
     *      summary="Удалить тест",
     *      description="Удалить тест",
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function destroy(int $id)
    {
        $this->repo->delete($id);

        return new SuccessResource(1);
    }
}
