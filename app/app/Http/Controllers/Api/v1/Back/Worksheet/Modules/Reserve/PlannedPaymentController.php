<?php

namespace App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Reserve;

use App\Http\Controllers\Controller;
use App\Http\Requests\Worksheet\Reserve\PlannedPaymentCreateRequest;
use App\Http\Resources\Default\SuccessResource;
use App\Http\Resources\Worksheet\Reserve\PlannedPaymentResource;
use App\Repositories\Worksheet\Modules\Reserve\PlannedPaymentRepository;
use Illuminate\Http\Request;

class PlannedPaymentController extends Controller
{
    public function __construct(
        private PlannedPaymentRepository $repo 
    )
    {
        
    }



    /**
     * @OA\Get(
     *      path="/worksheet/modules/reserves/planned",
     *      operationId="plannedList",
     *      tags={"Резерв"},
     *      summary="Получить планируему дату оплаты резерва (reserve_id => int|required)",
     *      description="Получить планируему дату оплаты резерва (reserve_id => int|required)",
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function index(Request $request)
    {   
        $validated = $request->validate([
            'reserve_id' => 'required',
        ]);

        $planned = $this->repo->getByReserve($validated['reserve_id']);
        
        if($planned)
            return new PlannedPaymentResource($planned);
        return response()->json([
            'data' => [],
            'success' => 1,
        ]);
    }



    /**
     * @OA\Post(
     *      path="/worksheet/modules/reserves/planned",
     *      operationId="plannedStore",
     *      tags={"Резерв"},
     *      summary="Создать планируему дату оплаты резерва (reserve_id => int, dealtype = int, date_at = d.m.Y))",
     *      description="Создать планируему дату оплаты резерва (reserve_id => int, dealtype = int, date_at = d.m.Y)",
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function store(PlannedPaymentCreateRequest $request)
    {
        $planned = $this->repo->create($request->validated());

        return new PlannedPaymentResource($planned);
    }



    /**
     * @OA\Patch(
     *      path="/worksheet/modules/reserves/planned/{id}",
     *      operationId="plannedUpdate",
     *      tags={"Резерв"},
     *      summary="Изменить планируему дату оплаты резерва (reserve_id => int, dealtype = int, date_at = d.m.Y))",
     *      description="Изменить планируему дату оплаты резерва (reserve_id => int, dealtype = int, date_at = d.m.Y)",
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function update(int $id, PlannedPaymentCreateRequest $request)
    {
        $planned = $this->repo->update($id, $request->validated());
        
        return new PlannedPaymentResource($planned);
    }



    /**
     * @OA\Delete(
     *      path="/worksheet/modules/reserves/planned/{id}",
     *      operationId="plannedDelete",
     *      tags={"Резерв"},
     *      summary="Удалить планируему дату оплаты резерва ",
     *      description="Удалить планируему дату оплаты резерва",
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function destroy(int $id)
    {
        $planned = $this->repo->delete($id);

        return new SuccessResource(1);
    }
}
