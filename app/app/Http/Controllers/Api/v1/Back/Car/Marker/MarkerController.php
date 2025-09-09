<?php

namespace App\Http\Controllers\Api\v1\Back\Car\Marker;

use App\Http\Controllers\Controller;
use App\Http\Requests\Car\Marker\MarkerCreateRequest;
use App\Http\Resources\Car\Marker\MarkerCollection;
use App\Http\Resources\Car\Marker\MarkerItemResource;
use App\Repositories\Car\Marker\MarkerRepository;
use Illuminate\Http\Request;
use App\Models\Marker;

class MarkerController extends Controller
{
    public function __construct(
        private MarkerRepository $repo,
        public $genus = 'male',
        public $subject = 'Маркер (Товарный признак)'
    )
    {
        $this->middleware('notice.message')->only(['store', 'update', 'delete', 'restore']);
    }



    /**
     * @OA\Get(
     *  path="/cars/markers",
     *  operationId="markersList",
     *  tags={"Маркер товарный признак"},
     *  summary="Список признаков",
     *  description="Список признаков(?trash)",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function index(Request $request) 
    {
        $markers = $this->repo->get($request->all());

        return new MarkerCollection($markers);
    }



    /**
     * @OA\Post(
     *  path="/cars/markers",
     *  operationId="markersStore",
     *  tags={"Маркер товарный признак"},
     *  summary="Создать признаков",
     *  description="Создать признаков(name, text_color, body_color, description)",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function store(MarkerCreateRequest $request)
    {
        $marker = $this->repo->store($request->validated());

        return (new MarkerItemResource($marker));
    }



    /**
     * @OA\Patch(
     *  path="/cars/markers/{markerId}",
     *  operationId="markersUpdate",
     *  tags={"Маркер товарный признак"},
     *  summary="Изменит признаков",
     *  description="Изменит признаков(name, text_color, body_color, description)",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function update(Marker $marker, MarkerCreateRequest $request) 
    {
        $this->repo->update($marker, $request->validated());
        
        return (new MarkerItemResource($marker));
    }



    /**
     * @OA\Get(
     *  path="/cars/markers/{markerId}",
     *  operationId="markersShow",
     *  tags={"Маркер товарный признак"},
     *  summary="Отрыт признаков",
     *  description="Отрыт признаков",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function show(Marker $marker) 
    {
        return (new MarkerItemResource($marker));
    }



    /**
     * @OA\Delete(
     *  path="/cars/markers/{markerId}",
     *  operationId="markersDelete",
     *  tags={"Маркер товарный признак"},
     *  summary="Удалить признаков",
     *  description="Удалить признаков",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function delete(Marker $marker) 
    {
        $this->repo->delete($marker);

        return response()->json(['success' => 1]);
    }



    /**
     * @OA\Patch(
     *  path="/cars/markers/{markerId}/restore",
     *  operationId="markersRestore",
     *  tags={"Маркер товарный признак"},
     *  summary="Востановитт признаков",
     *  description="Востановитт признаков",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function restore(Marker $marker) 
    {
        $this->repo->restore($marker);

        return new MarkerItemResource($marker);
    }
}
