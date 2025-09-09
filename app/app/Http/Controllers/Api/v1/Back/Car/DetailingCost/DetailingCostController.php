<?php

namespace App\Http\Controllers\Api\v1\Back\Car\DetailingCost;

use App\Http\Controllers\Controller;
use App\Models\DetailingCost;
use App\Repositories\Car\DetailingCost\DetailingCostRepository;
use App\Http\Requests\Car\DetailingCost\DetailingCostRequest;
use App\Http\Resources\Car\DetailingCost\DetailingCostCollection;
use App\Http\Resources\Car\DetailingCost\DetailingCostItemResource;
use Illuminate\Http\Request;

class DetailingCostController extends Controller
{
    public function __construct(
        private DetailingCostRepository $repo,
        public $genus = 'female',
        public $subject = 'Детализация цены'
    )
    {
        $this->middleware('notice.message')->only(['store', 'update', 'delete', 'restore']);
    }



    /**
     * @OA\Get(
     *  path="/cars/detailingcosts",
     *  operationId="detailingcostsList",
     *  tags={"Детализация цены автомобиля"},
     *  summary="Список",
     *  description="Список (?trash)",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function index(Request $request)
    {
        $costs = $this->repo->get($request->all());

        return new DetailingCostCollection($costs);
    }   
    
    

    /**
     * @OA\Post(
     *  path="/cars/detailingcosts",
     *  operationId="detailingcostsStore",
     *  tags={"Детализация цены автомобиля"},
     *  summary="Создать детализацию",
     *  description="Создать детализацию (name)",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function store(DetailingCost $detailingcost, DetailingCostRequest $request)
    {
        $this->repo->save($detailingcost, $request->validated());

        return (new DetailingCostItemResource($detailingcost));
    }



    /**
     * @OA\Patch(
     *  path="/cars/detailingcosts/{detailingCostId}",
     *  operationId="detailingcostsUpdate",
     *  tags={"Детализация цены автомобиля"},
     *  summary="Изменит детализацию",
     *  description="Изменит детализацию (name)",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function update(DetailingCost $detailingcost, DetailingCostRequest $request)
    {
        $this->repo->save($detailingcost, $request->validated());

        return (new DetailingCostItemResource($detailingcost));
    }



    /**
     * @OA\Get(
     *  path="/cars/detailingcosts/{detailingCostId}",
     *  operationId="detailingcostsShow",
     *  tags={"Детализация цены автомобиля"},
     *  summary="Открыть детализацию",
     *  description="Открыть детализацию",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function show(DetailingCost $detailingcost)
    {
        return (new DetailingCostItemResource($detailingcost));
    }



    /**
     * @OA\Delete(
     *  path="/cars/detailingcosts/{detailingCostId}",
     *  operationId="detailingcostsDelete",
     *  tags={"Детализация цены автомобиля"},
     *  summary="Удалить детализацию",
     *  description="Удалить детализацию",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function delete(DetailingCost $detailingcost)
    {
        $this->repo->delete($detailingcost);

        return response()->json([
            'success' => 1,
        ]);
    }



    /**
     * @OA\Patch(
     *  path="/cars/detailingcosts/{detailingCostId}/restore",
     *  operationId="detailingcostsRestore",
     *  tags={"Детализация цены автомобиля"},
     *  summary="Востановить детализацию",
     *  description="Востановить детализацию",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function restore(DetailingCost $detailingcost)
    {
        $this->repo->restore($detailingcost);

        return response()->json([
            'success' => 1,
        ]);
    }
}
