<?php

namespace App\Http\Controllers\Api\v1\Back\Worksheet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Worksheet\WorksheetStoreRequest;
use App\Http\Resources\Worksheet\WorksheetListCollection;
use App\Models\Worksheet;
use App\Repositories\Worksheet\WorksheetRepository;
use App\Http\Resources\Worksheet\WorksheetCreateResource;
use App\Http\Resources\Worksheet\WorksheetSaveResource;
use Illuminate\Http\Request;
use App\Services\Comment\Comment;

class WorksheetController extends Controller
{
    public function __construct(
        private WorksheetRepository $repo,
        public $subject = 'Рабочий лист',
        public $genus = 'male'
    )
    {
        $this->middleware('notice.message')->only(['store', 'close', 'revert']);
    }



    /**
     * @OA\Get(
     *  path="/worksheets",
     *  operationId="getListWorksheets",
     *  tags={"Рабочий лист"},
     *  summary="Список рабочих листов",
     *  description="Вернет список рабочих листов",
     *  @OA\Response(
     *      response=200,
     *      description="OK",
     *  ),
     * )
     */
    public function index(Request $request)
    {
        $worksheets = $this->repo->paginate($request->all(), 20);

        return new WorksheetListCollection($worksheets);
    }



    /**
     * @OA\Post(
     *  path="/worksheets",
     *  operationId="createWorksheet",
     *  tags={"Рабочий лист"},
     *  summary="Создать РЛ",
     *  description="Создать РЛ",
     *  @OA\RequestBody(
     *         @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/WorksheetStoreRequest",
     *         )
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="OK",
     *  ),
     * )
     */
    public function store(WorksheetStoreRequest $request)
    {
        $worksheet = $this->repo->createFromTrafic($request->trafic_id);

        Comment::add($worksheet->last_action, 'create');

        return new WorksheetCreateResource($worksheet);
    }



    /**
     * @OA\Get(
     *  path="/worksheets/{worksheetId}",
     *  operationId="showWorksheet",
     *  tags={"Рабочий лист"},
     *  summary="открыть РЛ",
     *  description="Открыть РЛ",
     *  @OA\Response(
     *      response=200,
     *      description="OK",
     *  ),
     * )
     */
    public function show($worksheet)
    {
        $worksheet = Worksheet::linksCount()->filesCount()->find($worksheet);

        Comment::add($worksheet->last_action, 'show');

        return new WorksheetSaveResource($worksheet);
    }



    /**
     * @OA\Get(
     *  path="/worksheet/close/{worksheetId}",
     *  operationId="closeWorksheet",
     *  tags={"Рабочий лист"},
     *  summary="Закрыть РЛ",
     *  description="Закрыть РЛ",
     *  @OA\Response(
     *      response=200,
     *      description="OK",
     *  ),
     * )
     */
    public function close(Worksheet $worksheet)
    {
        $this->repo->close($worksheet);

        return response()->json([
            'success'       => 1,
            'status'        => $worksheet->status->name,
            'status_id'     => $worksheet->status->id,
        ]);
    }



    /**
     * @OA\Get(
     *  path="/worksheet/revert/{worksheetId}",
     *  operationId="revertWorksheet",
     *  tags={"Рабочий лист"},
     *  summary="Востановить РЛ",
     *  description="Востановить РЛ",
     *  @OA\Response(
     *      response=200,
     *      description="OK",
     *  ),
     * )
     */
    public function revert(Worksheet $worksheet)
    {
        $this->repo->revert($worksheet);

        return response()->json(['success' => 1,]);
    }
}
