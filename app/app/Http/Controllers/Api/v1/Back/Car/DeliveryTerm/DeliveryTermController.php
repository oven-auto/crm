<?php

namespace App\Http\Controllers\Api\v1\Back\Car\DeliveryTerm;

use App\Http\Controllers\Controller;
use App\Http\Requests\Car\DeliveryTerm\DeliveryTermRequest;
use App\Http\Resources\Car\DeliveryTerm\DeliveryTermResource;
use App\Repositories\Car\DeliveryTerm\DeliveryTermRepository;
use App\Models\DeliveryTerm;
use Illuminate\Http\Request;

class DeliveryTermController extends Controller
{
    public function __construct(
        private DeliveryTermRepository $repo,
        public $subject = 'Условие доставки',
        public $genus = 'neuter'
    )
    {
        $this->middleware('notice.message')->only(['store', 'update', 'delete', 'restore']);
    }



    /**
     * @OA\Get(
     *  path="cars/deliveryterms",
     *  operationId="getDeliveryTermLists",
     *  tags={"CRUD Условия отгрузки"},
     *  summary="Получить список условий отгрузки",
     *  description="Получить список условий отгрузки",
     *  @OA\RequestBody(
     *      description="Описание параметров запроса",
     *      @OA\JsonContent(
     *           @OA\Property(
     *              property="trash", 
     *              type="string", 
     *              format="1", 
     *              description="Параметр отвечает за то, что бы выводить только удаленные"
     *          ),
     *      ),
     *  ),
     *  @OA\Response(
     *     response=200,
     *     description="OK"
     *  )
     * )
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'trash' => 'sometimes'
        ]);

        $terms = $this->repo->get($validated);

        return (DeliveryTermResource::collection($terms))
            ->additional(['success' => 1]);
    }



    /**
     * @OA\Post(
     *  path="cars/deliveryterms",
     *  operationId="storeDeliveryTermLists",
     *  tags={"CRUD Условия отгрузки"},
     *  summary="Добавить условие отгрузки.",
     *  description="Добавить условие отгрузки",
     *  @OA\RequestBody(
     *         @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/DeliveryTermRequest",
     *         )
     *     ),
     *  @OA\Response(
     *     response=200,
     *     description="OK"
     *  )
     * )
     */
    public function store(DeliveryTerm $deliveryterm, DeliveryTermRequest $request)
    {
        $this->repo->save($deliveryterm, $request->validated());

        return (new DeliveryTermResource($deliveryterm))
            ->additional([
                'success' => 1
            ]);
    }


    
    /**
     * @OA\Patch(
     *  path="cars/deliveryterms/{id}",
     *  operationId="updateDeliveryTermLists",
     *  tags={"CRUD Условия отгрузки"},
     *  summary="Изменить условие отгрузки.",
     *  description="Изменить условие отгрузки",
     *  @OA\Parameter(
     *          name="id",
     *          description="Идентификатор условия",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *  @OA\RequestBody(
     *         @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/DeliveryTermRequest",
     *         )
     *     ),
     *  @OA\Response(
     *     response=200,
     *     description="OK"
     *  )
     * )
     */
    public function update(DeliveryTerm $deliveryterm, DeliveryTermRequest $request)
    {
        $this->repo->save($deliveryterm, $request->validated());
        
        return (new DeliveryTermResource($deliveryterm))
            ->additional([
                'success' => 1
            ]);
    }



    /**
     * @OA\Get(
     *  path="cars/deliveryterms/{id}",
     *  operationId="showDeliveryTermLists",
     *  tags={"CRUD Условия отгрузки"},
     *  summary="Получить условие отгрузки.",
     *  description="Получить условие отгрузки",
     *  @OA\Parameter(
     *          name="id",
     *          description="Идентификатор условия",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *  @OA\Response(
     *     response=200,
     *     description="OK"
     *  )
     * )
     */
    public function show(DeliveryTerm $deliveryterm)
    {
        return (new DeliveryTermResource($deliveryterm))
            ->additional(['success' => 1]);
    }



    /**
     * @OA\Delete(
     *  path="cars/deliveryterms/{id}",
     *  operationId="deleteDeliveryTermLists",
     *  tags={"CRUD Условия отгрузки"},
     *  summary="Удалить условие отгрузки.",
     *  description="Удалить условие отгрузки",
     *  @OA\Parameter(
     *          name="id",
     *          description="Идентификатор условия",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *  @OA\Response(
     *     response=200,
     *     description="OK"
     *  )
     * )
     */
    public function delete(DeliveryTerm $deliveryterm)
    {
        $this->repo->delete($deliveryterm);

        return response()->json([
            'success' => 1,
        ]);
    }



    /**
     * @OA\Patch(
     *  path="cars/deliveryterms/{id}/restore",
     *  operationId="restoreDeliveryTermLists",
     *  tags={"CRUD Условия отгрузки"},
     *  summary="Востановить условие отгрузки.",
     *  description="Востановить условие отгрузки",
     *  @OA\Parameter(
     *          name="id",
     *          description="Идентификатор условия",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *  @OA\Response(
     *     response=200,
     *     description="OK"
     *  )
     * )
     */
    public function restore(DeliveryTerm $deliveryterm)
    {
        $this->repo->restore($deliveryterm);

        return response()->json([
            'success' => 1,
        ]);
    }

}
