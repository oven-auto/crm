<?php

namespace App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Credit;

use App\Http\Controllers\Controller;
use App\Http\Requests\Worksheet\Credit\CreditListRequest;
use App\Http\Resources\Worksheet\Credit\CreditCollection;
use App\Repositories\Worksheet\Modules\Credit\CreditRepository;
use Illuminate\Http\Request;

class CreditListController extends Controller
{
    public function __construct(
        private CreditRepository $repo
    )
    {
        
    }



    /**
     * @OA\Get(
     *      path="/creditlist",
     *      operationId="wcreditJournal",
     *      tags={"МОДУЛЬ РЛ: Кредит"},
     *      summary="Журнал ",
     *      description="Журнал ",
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/CreditListRequest",
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function index(CreditListRequest $request)
    {
        $result = $this->repo->paginate($request->all());

        return new CreditCollection($result);
    }



        /**
     * @OA\Get(
     *      path="/creditlist/count",
     *      operationId="creditCount",
     *      tags={"МОДУЛЬ РЛ: Кредит"},
     *      summary="Журнал (кол-во) ",
     *      description="Журнал (кол-во) ",
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/CreditListRequest",
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function count(Request $request)
    {
        $count = $this->repo->count($request->all());

        return response()->json([
            'count' => $count,
            'success' => 1,
        ]);
    }
}
