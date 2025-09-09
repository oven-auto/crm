<?php

namespace App\Http\Controllers\Api\v1\Back\Car\OrderType;

use App\Http\Controllers\Controller;
use App\Models\OrderType;
use App\Http\Requests\Car\OrderType\OrderTypeRequest;
use App\Http\Resources\Car\OrderType\OrderTypeCollection;
use App\Http\Resources\Car\OrderType\OrderTypeItemResource;
use App\Repositories\Car\OrderType\OrderTypeRepository;
use Illuminate\Http\Request;

class OrderTypeController extends Controller
{
    public function __construct(
        private OrderTypeRepository $repo,
        public $genus = 'male',
        public $subject = 'Типы заказа автомобиля'
    )
    {
        $this->middleware('notice.message')->only(['store', 'update', 'delete', 'restore']);
    }



    /**
     * @OA\Get(
     *  path="/cars/ordertypes",
     *  operationId="ordertypesList",
     *  tags={"Типы заказа автомобиля"},
     *  summary="Список Типы заказа автомобиля",
     *  description="Список Типы заказа автомобиля(?trash)",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function index(Request $request)
    {
        $orderTypes = $this->repo->get($request->all());

        return new OrderTypeCollection($orderTypes);
    }



    /**
     * @OA\Post(
     *  path="/cars/ordertypes",
     *  operationId="ordertypesStore",
     *  tags={"Типы заказа автомобиля"},
     *  summary="создать Типы заказа автомобиля",
     *  description="создать Типы заказа автомобиля(name, text_color, description)",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function store(OrderType $ordertype, OrderTypeRequest $request)
    {
        $this->repo->save($ordertype, $request->validated());

        return (new OrderTypeItemResource($ordertype));
    }


    
    /**
     * @OA\Patch(
     *  path="/cars/ordertypes/{orderId}",
     *  operationId="ordertypesUpdate",
     *  tags={"Типы заказа автомобиля"},
     *  summary="Изменить Типы заказа автомобиля",
     *  description="Изменить Типы заказа автомобиля(name, text_color, description)",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function update(OrderType $ordertype, OrderTypeRequest $request)
    {
        $this->repo->save($ordertype, $request->validated());

        return (new OrderTypeItemResource($ordertype));
    }



    /**
     * @OA\Get(
     *  path="/cars/ordertypes/{orderId}",
     *  operationId="ordertypesShow",
     *  tags={"Типы заказа автомобиля"},
     *  summary="Отркыть Типы заказа автомобиля",
     *  description="Отркыть Типы заказа автомобиля",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function show(OrderType $ordertype)
    {
        return (new OrderTypeItemResource($ordertype));
    }



    /**
     * @OA\Delete(
     *  path="/cars/ordertypes/{orderId}",
     *  operationId="ordertypesDelete",
     *  tags={"Типы заказа автомобиля"},
     *  summary="Удалить Типы заказа автомобиля",
     *  description="Удалить Типы заказа автомобиля",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function delete(OrderType $ordertype)
    {
        $this->repo->delete($ordertype);

        return response()->json(['success' => 1,]);
    }



    /**
     * @OA\Patch(
     *  path="/cars/ordertypes/{orderId}/restore",
     *  operationId="ordertypesRestore",
     *  tags={"Типы заказа автомобиля"},
     *  summary="Востановитв Типы заказа автомобиля",
     *  description="Востановитв Типы заказа автомобиля",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function restore(OrderType $ordertype)
    {
        $this->repo->restore($ordertype);

        return response()->json(['success' => 1,]);
    }
}
