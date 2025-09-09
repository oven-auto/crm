<?php

namespace App\Http\Controllers\Api\v1\Back\DiscountCar;

use App\Http\Controllers\Controller;
use App\Http\Requests\Discount\DiscountListRequest;
use App\Http\Resources\Worksheet\Reserve\SaleReserveItemCollection;
use App\Http\Resources\Worksheet\Reserve\SaleReserveItemResource;
use App\Models\Discount;
use App\Repositories\Discount\DiscountRepository;

class DiscountListController extends Controller
{
    private $repo;

    public function __construct(DiscountRepository $repo)
    {
        $this->repo = $repo;
    }



    /**
     * @OA\Get(
     *  path="/discountlist",
     *  tags={"Журнал скидок"},
     *  operationId="getDiscountList",
     *  summary="Журнал скидок",
     *  description="Журнал скидок",
     *  @OA\RequestBody(
     *      @OA\JsonContent(
     *          type="object",
     *          ref="#/components/schemas/DiscountListRequest",
     *      )
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     */
    public function index(DiscountListRequest $request)
    {
        $discounts = $this->repo->paginate($request->all());
        
        return new SaleReserveItemCollection($discounts);
    }



    /**
     * @OA\Get(
     *  path="/discountlist/count",
     *  tags={"Журнал скидок"},
     *  operationId="getDiscountCountList",
     *  summary="Количество скидок в журнале",
     *  description="Количество скидок в журнале",
     *  @OA\RequestBody(
     *      @OA\JsonContent(
     *          type="object",
     *          ref="#/components/schemas/DiscountListRequest",
     *      )
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     */
    public function count(DiscountListRequest $request)
    {
        $count = $this->repo->count($request->all());

        return response()->json([
            'count' => $count,
            'success' => 1,
            'message' => '',
        ]);
    }



    public function show(Discount $discount) {}



    public function update(Discount $discount) {}
}
