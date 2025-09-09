<?php

namespace App\Http\Controllers\Api\v1\Back\TargetModel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Target\TargetCreateRequest;
use App\Http\Resources\Default\SuccessResource;
use App\Http\Resources\Target\TargetEditResource;
use App\Http\Resources\Target\TargetListCollection;
use App\Repositories\Target\TargetRepository;
use Illuminate\Http\Request;

class TargetModelController extends Controller
{
    public function __construct(
        private TargetRepository $repo
    )
    {
        
    }



     /**
     * @OA\Get(
     *  path="/targets",
     *  tags={"Цели"},
     *  operationId="getTargetList",
     *  summary="Журнал целей",
     *  description="Журнал целей",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     */
    public function index(Request $request)
    {
        $targets = $this->repo->paginate($request->all());

        return new TargetListCollection($targets);
    }



    /**
     * @OA\Post(
     *  path="/targets",
     *  tags={"Цели"},
     *  operationId="storeTarget",
     *  summary="Добавить цель",
     *  description="Добавить цель",
     *  @OA\RequestBody(
     *      @OA\JsonContent(
     *          type="object",
     *          ref="#/components/schemas/TargetCreateRequest",
     *      )
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     */
    public function store(TargetCreateRequest $request)
    {
        $target = $this->repo->store($request->validated());
        
        return new TargetEditResource($target);
    }



    /**
     * @OA\Patch(
     *  path="/targets/{id}",
     *  tags={"Цели"},
     *  operationId="updateTarget",
     *  summary="Изменить цель",
     *  description="Изменить цель",
     *  @OA\RequestBody(
     *      @OA\JsonContent(
     *          type="object",
     *          ref="#/components/schemas/TargetCreateRequest",
     *      )
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     */
    public function update(int $id, TargetCreateRequest $request)
    {
        $target = $this->repo->update($id, $request->validated());
        
        return new TargetEditResource($target);
    }



    /**
     * @OA\Get(
     *  path="/targets/{id}",
     *  tags={"Цели"},
     *  operationId="showTarget",
     *  summary="Открыть цель",
     *  description="Открыть цель",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     */
    public function show(int $id)
    {
        $target = $this->repo->getById($id);

        return new TargetEditResource($target);
    }



     /**
     * @OA\Delete(
     *  path="/targets/{id}",
     *  tags={"Цели"},
     *  operationId="deleteTarget",
     *  summary="Удалить цель",
     *  description="Удалить цель",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     */
    public function destroy(int $id)
    {
        $this->repo->delete($id);

        return new SuccessResource(1);
    }



    public function count()
    {
        return response()->json([
            'count' => 1,
            'success' => 1,
        ]);
    }
}
