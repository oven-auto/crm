<?php

namespace App\Http\Controllers\Api\v1\Back\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\Service\ServiceCreateRequest;
use App\Http\Resources\Default\SuccessResource;
use App\Http\Resources\Service\ServiceCollection;
use App\Http\Resources\Service\ServiceItemResource;
use App\Repositories\Services\ServiceRepository;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function __construct(
        private ServiceRepository $repo,
        public $subject = 'Сервис',
        public $genus = 'male'
    )
    {
        $this->middleware('notice.message')->only(['store', 'update', 'delete', 'restore']);
    }



    /**
     * @OA\Get(
     *      path="/finservices",
     *      operationId="getfinservices",
     *      tags={"Финансовые сервисы"},
     *      summary="Список",
     *      description="Вернет список (module_id ?, category_id ?)",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function index(Request $request)
    {
        $result  = $this->repo->getAll($request->all());

        return new ServiceCollection($result);
    }



    /**
     * @OA\Get(
     *      path="/finservices/{id}",
     *      operationId="showfinservices",
     *      tags={"Финансовые сервисы"},
     *      summary="Открыть ",
     *      description="Вернет ",
     *      @OA\Parameter(
     *          name="id",
     *          description="Идентификатор кузова",
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
    public function show(int $id)
    {
        $service = $this->repo->getById($id);
        
        return new ServiceItemResource($service);
    }



    /**
     * @OA\Post(
     *      path="/finservices",
     *      operationId="createfinservices",
     *      tags={"Финансовые сервисы"},
     *      summary="Создать ",
     *      description="Создать ",
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/ServiceCreateRequest",
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function store(ServiceCreateRequest $request)
    {
        $service = $this->repo->create($request->getDTO());

        return new ServiceItemResource($service);
    }



    /**
     * @OA\Patch(
     *      path="/finservices/{id}",
     *      operationId="updatefinservices",
     *      tags={"Финансовые сервисы"},
     *      summary="Изменить ",
     *      description="Изменить ",
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/ServiceCreateRequest",
     *         )
     *     ),
     *      @OA\Parameter(
     *          name="id",
     *          description="Идентификатор кузова",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      )
     * )
     */
    public function update(int $id, ServiceCreateRequest $request)
    {
        $service = $this->repo->update($id, $request->getDTO());

        return new ServiceItemResource($service);
    }



        /**
     * @OA\Delete(
     *      path="/finservices/{id}",
     *      operationId="deletefinservices",
     *      tags={"Финансовые сервисы"},
     *      summary="Delete ",
     *      @OA\Parameter(
     *          name="id",
     *          description="Идентификатор кузова",
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
    public function destroy(int $id)
    {
        $this->repo->delete($id);

        return new SuccessResource(1);
    }



        /**
     * @OA\Patch(
     *      path="/finservices/{id}/restore",
     *      operationId="restorefinservices",
     *      tags={"Финансовые сервисы"},
     *      summary="Restore ",
     *      @OA\Parameter(
     *          name="id",
     *          description="Идентификатор кузова",
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
    public function restore(int $id)
    {
        $service = $this->repo->restore($id);

        return new ServiceItemResource($service);
    }
}
