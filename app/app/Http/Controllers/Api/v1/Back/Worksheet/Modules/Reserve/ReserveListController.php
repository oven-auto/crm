<?php

namespace App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Reserve;

use App\Http\Controllers\Controller;
use App\Http\Resources\Car\CarCountResource;
use App\Http\Resources\Worksheet\Reserve\ReserveList\ReserveCollection;
use App\Repositories\Worksheet\Modules\Reserve\ReserveRepository;
use Illuminate\Http\Request;

class ReserveListController extends Controller
{
    public function __construct()
    {
        $this->middleware('carfilter')->only(['index', 'count']);
    }



    /**
     * @OA\Get(
     *      path="/reserves",
     *      operationId="reserveList",
     *      tags={"Резерв"},
     *      summary="Список Резервов новых автомобилей",
     *      description="Список Резервов новых автомобилейв",
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/ReserveNewCarFilter",
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function index(ReserveRepository $repo, Request $request)
    {
        $reserves = $repo->paginate($request->all());
        
        return new ReserveCollection($reserves);
    }



    /**
     * @OA\Get(
     *      path="/reserves/count",
     *      operationId="reserveListCount",
     *      tags={"Резерв"},
     *      summary="Счетчик Резервов новых автомобилей",
     *      description="Счетчик Резервов новых автомобилейв",
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/ReserveNewCarFilter",
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function count(ReserveRepository $repo, Request $request)
    {
        $res = $repo->counter($request->all());

        return new CarCountResource($res);
    }
}
