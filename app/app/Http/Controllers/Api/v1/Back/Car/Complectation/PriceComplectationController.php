<?php

namespace App\Http\Controllers\Api\v1\Back\Car\Complectation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Car\Complectation\ComplectationPriceListRequest;
use App\Http\Requests\Car\Complectation\PriceComplectationSaveRequest;
use App\Http\Resources\Car\Complectation\PriceCreateResource;
use App\Http\Resources\Car\Complectation\PriceItemCollection;
use App\Models\ComplectationPrice;
use App\Repositories\Car\Complectation\PriceComplectationRepository;

class PriceComplectationController extends Controller
{
    public function __construct(
        private PriceComplectationRepository $repo,
        public $genus = 'female',
        public $subject = 'Цена комплектации'
    )
    {
        $this->middleware('notice.message')->only(['store', ]);
    }



    /**
     * @OA\Get(
     *  path="/cars/complectations/prices",
     *  operationId="complectationPricesList",
     *  tags={"Комплектации новых автомобилей"},
     *  summary="Список цен комплектации",
     *  description="Список цен комплектации (?complectation_id, ?car_id)",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function index(ComplectationPriceListRequest $request)
    {
        $complectationprices = $this->repo->get($request->validated());

        return new PriceItemCollection($complectationprices);
    }



    /**
     * @OA\Post(
     *  path="/cars/complectations/prices",
     *  operationId="complectationPricesListStore",
     *  tags={"Комплектации новых автомобилей"},
     *  summary="Создать цену комплектации",
     *  description="Создать цену комплектации (complectation_id, price, begin_at)",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function store(ComplectationPrice $complectationprice, PriceComplectationSaveRequest $request)
    {
        $this->repo->save($complectationprice, $request->validated());

        return new PriceCreateResource($complectationprice);
    }



    /**
     * @OA\Get(
     *  path="/cars/complectations/prices/{priceId}",
     *  operationId="complectationPricesListShow",
     *  tags={"Комплектации новых автомобилей"},
     *  summary="Открыть цену комплектации",
     *  description="Открыть цену комплектации",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function show(ComplectationPrice $complectationprice)
    {
        return new PriceCreateResource($complectationprice);
    }
}
