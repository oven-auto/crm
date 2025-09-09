<?php

namespace App\Http\Controllers\Api\v1\Back\Car\TradeMarker;

use App\Http\Controllers\Controller;
use App\Http\Requests\Car\Trademarker\TradeMarkerCreateRequest;
use App\Http\Resources\Car\Marker\MarkerCollection;
use App\Http\Resources\Car\Marker\MarkerItemResource;
use App\Models\TradeMarker;
use App\Repositories\Car\TradeMarker\TradeMarkerRepository;
use Illuminate\Http\Request;

class TradeMarkerController extends Controller
{
    public function __construct(
        private TradeMarkerRepository $repo,
        public $genus = 'male',
        public $subject = 'Контрмарка',
    )
    {
        $this->middleware('notice.message')->only(['store', 'update', 'delete', 'restore']);
    }



    /**
     * @OA\Get(
     *  path="/cars/trademarkers",
     *  operationId="trademarkersList",
     *  tags={"Контрмарка"},
     *  summary="Список контрмарок",
     *  description="Список контрмарок(?trash)",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function index(Request $request): MarkerCollection
    {
        $markers = $this->repo->get($request->all());

        return new MarkerCollection($markers);
    }



    /**
     * @OA\Post(
     *  path="/cars/trademarkers",
     *  operationId="trademarkersStore",
     *  tags={"Контрмарка"},
     *  summary="Создать контрмарок",
     *  description="Создать контрмарок(name, text_color, ?description)",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function store(TradeMarker $marker, TradeMarkerCreateRequest $request)
    {
        $this->repo->save($marker, $request->validated());
        
        return (new MarkerItemResource($marker));
    }



    /**
     * @OA\Patch(
     *  path="/cars/trademarkers/{tradeMarkerId}",
     *  operationId="trademarkersUpdate",
     *  tags={"Контрмарка"},
     *  summary="Изменить контрмарок",
     *  description="Изменить контрмарок(name, text_color, ?description)",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function update(TradeMarker $marker, Request $request): MarkerItemResource
    {
        $this->repo->save($marker, $request->validated());
        
        return (new MarkerItemResource($marker));
    }



    /**
     * @OA\Get(
     *  path="/cars/trademarkers/{tradeMarkerId}",
     *  operationId="trademarkersShow",
     *  tags={"Контрмарка"},
     *  summary="Открыть контрмарок",
     *  description="Открыть контрмарок",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function show(TradeMarker $marker): MarkerItemResource
    {
        return (new MarkerItemResource($marker));
    }



    /**
     * @OA\Delete(
     *  path="/cars/trademarkers/{tradeMarkerId}",
     *  operationId="trademarkersDelete",
     *  tags={"Контрмарка"},
     *  summary="Удалить контрмарок",
     *  description="Удалить контрмарок",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function delete(TradeMarker $marker): \Illuminate\Http\JsonResponse
    {
        $this->repo->delete($marker);

        return response()->json(['success' => 1]);
    }



    /**
     * @OA\Patch(
     *  path="/cars/trademarkers/{tradeMarkerId}/restore",
     *  operationId="trademarkersRestore",
     *  tags={"Контрмарка"},
     *  summary="Востановить контрмарок",
     *  description="Востановить контрмарок",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function restore(TradeMarker $marker): MarkerItemResource
    {
        $this->repo->restore($marker);

        return (new MarkerItemResource($marker));
    }
}
