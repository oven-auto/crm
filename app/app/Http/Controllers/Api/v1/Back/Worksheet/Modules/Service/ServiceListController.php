<?php

namespace App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\Worksheet\Service\ServiceListRequest;
use App\Http\Resources\Worksheet\Service\ServiceCollection;
use App\Repositories\Worksheet\Modules\Service\ServiceWorksheetRepository;

class ServiceListController extends Controller
{
    public function __construct(
        private ServiceWorksheetRepository $repo
    )
    {
        
    }



    /**
     * @OA\Get(
     *      path="/servicelist",
     *      operationId="worksheetServiceJournal",
     *      tags={"МОДУЛЬ РЛ: Финансовые сервисы"},
     *      summary="Журнал ",
     *      description="Журнал ",
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/ServiceListRequest",
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function index(ServiceListRequest $request)
    {
        $services = $this->repo->paginate($request->validated());
        
        return new ServiceCollection($services);
    }



    /**
     * @OA\Get(
     *      path="/servicelist/count",
     *      operationId="worksheetServiceCount",
     *      tags={"МОДУЛЬ РЛ: Финансовые сервисы"},
     *      summary="Журнал (кол-во) ",
     *      description="Журнал (кол-во) ",
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/ServiceListRequest",
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function count(ServiceListRequest $request)
    {
        $count = $this->repo->count($request->validated());

        return response()->json([
            'count' => [
                'count' => (int) $count->_count,
                'cost'  => (int) $count->_cost,
                'award' => (int) $count->_award,
                'deduction' => (int) $count->_deduction,
            ],
            'success' => 1,
        ]);
    }
}
