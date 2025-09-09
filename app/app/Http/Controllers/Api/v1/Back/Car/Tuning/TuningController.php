<?php

namespace App\Http\Controllers\Api\v1\Back\Car\Tuning;

use App\Http\Controllers\Controller;
use App\Http\Requests\Car\Tuning\TuningSaveRequest;
use App\Http\Resources\Car\TuningCollection;
use App\Http\Resources\Car\TuningItemResource;
use Illuminate\Http\Request;
use App\Models\Tuning;
use App\Repositories\Car\Tuning\TuningRepository;

class TuningController extends Controller
{
    public function __construct(
        private TuningRepository $repo,
        public $genus = 'male',
        public $subject = 'Тюнинг'
    )
    {
        $this->middleware('notice.message')->only(['store', 'update', 'delete', 'restore']);
    }



    /**
     * @OA\Get(
     *  path="/cars/tunings",
     *  operationId="tuningsList",
     *  tags={"Тюнинг"},
     *  summary="Список Тюнинга",
     *  description="Список Тюнинга(?trash)",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function index(Request $request)
    {
        $tunings = $this->repo->get($request->all());

        return new TuningCollection($tunings);
    }



    /**
     * @OA\Post(
     *  path="/cars/tunings",
     *  operationId="tuningsStore",
     *  tags={"Тюнинг"},
     *  summary="Создать Тюнинга",
     *  description="Создать Тюнинга(name)",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function store(Tuning $tuning, TuningSaveRequest $request)
    {
        $this->repo->save($tuning, $request->validated());

        return (new TuningItemResource($tuning));
    }



    /**
     * @OA\Patch(
     *  path="/cars/tunings/{tuningId}",
     *  operationId="tuningsUpdate",
     *  tags={"Тюнинг"},
     *  summary="Изменить Тюнинга",
     *  description="Изменить Тюнинга(name)",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function update(Tuning $tuning, TuningSaveRequest $request)
    {
        $this->repo->save($tuning, $request->validated());

        return (new TuningItemResource($tuning));
    }



    /**
     * @OA\Get(
     *  path="/cars/tunings/{tuningId}",
     *  operationId="tuningsShow",
     *  tags={"Тюнинг"},
     *  summary="Открыть Тюнинга",
     *  description="Открыть Тюнинга",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function show(Tuning $tuning)
    {
        return (new TuningItemResource($tuning));
    }



    /**
     * @OA\Delete(
     *  path="/cars/tunings/{tuningId}",
     *  operationId="tuningsDelete",
     *  tags={"Тюнинг"},
     *  summary="Удалить Тюнинга",
     *  description="Удалить Тюнинга",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function destroy(Tuning $tuning)
    {
        $this->repo->destroy($tuning);

        return response()->json([ 'success' => 1,]);
    }



    /**
     * @OA\Patch(
     *  path="/cars/tunings/{tuningId}/restore",
     *  operationId="tuningsRestore",
     *  tags={"Тюнинг"},
     *  summary="Востановить Тюнинга",
     *  description="Востановить Тюнинга",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function restore(Tuning $tuning)
    {
        $this->repo->restore($tuning);

        return response()->json(['success' => 1,]);
    }
}
