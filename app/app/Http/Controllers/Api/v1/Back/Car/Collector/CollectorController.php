<?php

namespace App\Http\Controllers\Api\v1\Back\Car\Collector;

use App\Http\Controllers\Controller;
use App\Http\Requests\Car\Collector\CollectorCreateRequest;
use App\Http\Resources\Car\Collector\CollectorResource;
use App\Http\Resources\Car\Collector\CollectorSaveResource;
use App\Models\Collector;
use App\Repositories\Car\Collector\CollectorRepository;
use Illuminate\Http\Request;

class CollectorController extends Controller
{
    public function __construct(
        private CollectorRepository $repo,
        public $genus = 'male',
        public $subject = 'Держатель залога'
    )
    {
        $this->middleware('notice.message')->only(['store', 'update', 'destroy', 'revert']);
    }


    
    /**
     * @OA\Get(
     *      path="/cars/collectors/",
     *      operationId="getCollectorList",
     *      tags={"CRUD Держатель залога"},
     *      summary="Список держателей залога",
     *      description="Список держателей залога",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function index(Request $request)
    {
        $collectors = $this->repo->get($request->all());

        return response()->json([
            'data' => CollectorResource::collection($collectors),
            'success' => 1,
        ]);
    }



    /**
     * @OA\Post(
     *      path="/cars/collectors/",
     *      operationId="storeCollectorList",
     *      tags={"CRUD Держатель залога"},
     *      summary="Создать держателя залога",
     *      description="Создать держателя залога (name = string)",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function store(CollectorCreateRequest $request)
    {
        $collector = $this->repo->create(dto: $request->getDTO());

        return (new CollectorSaveResource($collector));
    }



     /**
     * @OA\Patch(
     *      path="/cars/collectors/{collectorId}",
     *      operationId="updateCollectorList",
     *      tags={"CRUD Держатель залога"},
     *      summary="Изменить держателя залога",
     *      description="Изменить держателя залога (name = string)",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function update(int $collector, CollectorCreateRequest $request)
    {
        $collector = $this->repo->update(id: $collector, dto: $request->getDTO());

        return (new CollectorSaveResource($collector));
    }



     /**
     * @OA\Get(
     *      path="/cars/collectors/{collectorId}",
     *      operationId="showCollectorList",
     *      tags={"CRUD Держатель залога"},
     *      summary="Открыть держателя залога",
     *      description="Открыть держателя залога",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function show(int $collector)
    {
        $collector = $this->repo->getById(id: $collector);

        return (new CollectorSaveResource($collector));
    }



     /**
     * @OA\Delete(
     *      path="/cars/collectors/{collectorId}",
     *      operationId="deleteCollectorList",
     *      tags={"CRUD Держатель залога"},
     *      summary="Удалить держателя залога",
     *      description="Удалить держателя залога",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function destroy(int $collector)
    {
        $this->repo->delete(id: $collector);

        return response()->json([ 'success' => 1,]);
    }



     /**
     * @OA\Patch(
     *      path="/cars/collectors/{collectorId}/restore",
     *      operationId="restoreCollectorList",
     *      tags={"CRUD Держатель залога"},
     *      summary="Востановить держателя залога",
     *      description="Востановить держателя залога",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function revert(Collector $collector)
    {
        $collector->restore();

        return response()->json(['success' => 1,]);
    }
}
