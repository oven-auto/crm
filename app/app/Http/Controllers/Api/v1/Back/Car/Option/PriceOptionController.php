<?php

namespace App\Http\Controllers\Api\v1\Back\Car\Option;

use App\Http\Controllers\Controller;
use App\Http\Requests\Car\Option\OptionPriceCreateRequest;
use App\Http\Requests\Car\Option\OptionPriceIndexRequest;
use App\Http\Resources\Car\Option\OptionPriceEditResource;
use App\Http\Resources\Car\Option\OptionPriceResource;
use App\Models\OptionPrice;
use App\Repositories\Car\Option\PriceOptionRepository;

class PriceOptionController extends Controller
{
    public function __construct(
        private PriceOptionRepository $repo,
        public $genus = 'female',
        public $subject = 'Цена опции'
    )
    {
        $this->middleware('notice.message')->only(['store', 'update']);
    }



    /**
     * @OA\Get(
     *  path="/cars/options/prices",
     *  operationId="optionsPriceList",
     *  tags={"Опции автомобиля"},
     *  summary="Список цен опций",
     *  description="Список цен опций(option_id, ?car_id)",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function index(OptionPriceIndexRequest $request)
    {
        $prices = $this->repo->get($request->validated());
        
        return new OptionPriceResource($prices);
    }



    /**
     * @OA\Post(
     *  path="/cars/options/prices",
     *  operationId="optionsPriceStore",
     *  tags={"Опции автомобиля"},
     *  summary="Добавить цен опций",
     *  description="Добавить цен опций(option_id, begin_at, price)",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function store(OptionPrice $optionPrice, OptionPriceCreateRequest $request)
    {
        $this->repo->save($optionPrice, $request->validated());

        return new OptionPriceEditResource($optionPrice);
    }



    /**
     * @OA\Get(
     *  path="/cars/options/prices/{priceId}",
     *  operationId="optionsPriceShow",
     *  tags={"Опции автомобиля"},
     *  summary="Открыт цен опций",
     *  description="Открытб цен опций",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function show(OptionPrice $optionPrice)
    {
        return new OptionPriceEditResource($optionPrice);
    }
}
