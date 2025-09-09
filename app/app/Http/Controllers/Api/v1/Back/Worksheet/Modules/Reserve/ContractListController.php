<?php

namespace App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Reserve;

use App\Http\Controllers\Controller;
use App\Http\Resources\Worksheet\Reserve\Contract\ContractListCollection;
use App\Repositories\Worksheet\Modules\Reserve\ReserveContractRepository;
use Illuminate\Http\Request;

class ContractListController extends Controller
{
    /**
     * @OA\Get(
     *      path="/contracts",
     *      operationId="contractList",
     *      tags={"Контракты"},
     *      summary="Список Контракты новых автомобилей",
     *      description="Список Контракты новых автомобилейв",
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/ContractFilter",
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function index(Request $request, ReserveContractRepository $repo)
    {
        $contracts = $repo->paginate($request->all());
        
        return new ContractListCollection($contracts);
    }



    public function count(Request $request, ReserveContractRepository $repo)
    {
        $result = $repo->counter($request->all());
        
        return response()->json([
            'count' => $result,
            'success' => 1,
            'message' => '',
        ]);
    }
}
