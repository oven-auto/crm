<?php

namespace App\Http\Controllers\Api\v1\Back\Car\Complectation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Car\Complectation\ComplectationListRequest;
use App\Http\Resources\Car\Complectation\ComplectationListResource;
use App\Repositories\Car\Complectation\ComplectationRepository;
use Illuminate\Http\Request;

class ComplectationListController extends Controller
{
    public function __construct(
        private ComplectationRepository $repo
    )
    {
        
    }



    /**
     * @OA\Get(
     *  path="/complectationlist",
     *  tags={"Комплектации новых автомобилей"},
     *  operationId="getcomplectationlist",
     *  summary="Журнал комплектации",
     *  description="Журнал комплектации",
     *  @OA\RequestBody(
     *     @OA\JsonContent(
     *         type="object",
     *         ref="#/components/schemas/ComplectationListRequest",
     *     )
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     */
    public function index(ComplectationListRequest $request)
    {
        $complectations = $this->repo->list($request->all());
        
        $date = $this->repo->maxComplectationDatePrice();

        return response()->json([
            'data' => ComplectationListResource::collection($complectations),
            'last_price' => $date,
            'success' => 1,
        ]);
    }



    /**
     * @OA\Get(
     *  path="/complectationlist/count",
     *  tags={"Комплектации новых автомобилей"},
     *  operationId="getcomplectationlistCount",
     *  summary="Счетчик журнала комплектации",
     *  description="Счетчик журнала комплектации",
     *  @OA\RequestBody(
     *     @OA\JsonContent(
     *         type="object",
     *         ref="#/components/schemas/ComplectationListRequest",
     *     )
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     */
    public function count(Request $request)
    {
        $count = $this->repo->count($request->all());

        return response()->json([
            'count' =>$count,
            'success' => 1,
        ]);
    }
}
