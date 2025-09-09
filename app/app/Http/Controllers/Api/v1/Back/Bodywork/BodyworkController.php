<?php

namespace App\Http\Controllers\Api\v1\Back\Bodywork;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bodywork\BodyworkSaveRequest;
use App\Http\Resources\Bodywork\BodyworkSaveResource;
use App\Models\BodyWork;
use App\Repositories\Bodywork\BodyworkRepository;
use OpenApi\Annotations as OA;

class BodyworkController extends Controller
{
    private $repo;

    public function __construct(BodyworkRepository $repo)
    {
        $this->repo = $repo;
    }



    /**
     * @OA\Get(
     *      path="/bodyworks",
     *      operationId="getBodyworkList",
     *      tags={"Кузов"},
     *      summary="Список кузовов",
     *      description="Вернет список кузавов",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function index() : \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'data' => BodyWork::get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'vehicle' => $item->vehicle->name,
                    'acronym' => $item->acronym
                ];
            }),
            'success' => 1,
        ]);
    }



    /**
     * @OA\Post(
     *      path="/bodyworks",
     *      operationId="createBodywork",
     *      tags={"Кузов"},
     *      summary="Создать кузов",
     *      description="Создать новый кузов",
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/BodyworkSaveRequest",
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function store(BodyWork $bodywork, BodyworkSaveRequest $request)
    {
        $this->repo->save($bodywork, $request->validated());

        return response()->json([
            'data' => new BodyworkSaveResource($bodywork),
            'success' => 1,
            'message' => 'Кузов добавлен.'
        ]);
    }



    /**
     * @OA\Get(
     *      path="/bodyworks/{id}",
     *      operationId="getItemBodywork",
     *      tags={"Кузов"},
     *      summary="Открыть выбранный кузов",
     *      description="Вернет выбранный кузов",
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
    public function show(BodyWork $bodywork)
    {
        return response()->json([
            'data' => new BodyworkSaveResource($bodywork),
            'success' => 1,
        ]);
    }



     /**
     * @OA\Put(
     *      path="/bodyworks/{id}",
     *      operationId="updateBodywork",
     *      tags={"Кузов"},
     *      summary="Изменить выбранный кузов",
     *      description="Изменить выбранный кузов",
     *      @OA\Parameter(
     *          name="id",
     *          description="Идентификатор кузова",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/BodyworkSaveRequest",
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      )
     * )
     */
    public function update(BodyWork $bodywork, BodyworkSaveRequest $request)
    {
        $this->repo->save($bodywork, $request->validated());

        return response()->json([
            'data' => new BodyworkSaveResource($bodywork),
            'success' => 1,
            'message' => 'Кузов изменен.'
        ]);
    }
}
