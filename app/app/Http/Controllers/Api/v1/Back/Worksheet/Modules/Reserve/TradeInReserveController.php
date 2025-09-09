<?php

namespace App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Reserve;

use App\Http\Controllers\Controller;
use App\Http\Resources\UsedCar\UsedCarItemResource;
use App\Models\WsmReserveNewCar;
use App\Repositories\Worksheet\Modules\Reserve\ReserveRepository;
use Illuminate\Http\Request;

class TradeInReserveController extends Controller
{
    private $repo;

    public function __construct(ReserveRepository $repo)
    {
        $this->repo = $repo;
    }



    public function attach(WsmReserveNewCar $reserve, Request $request)
    {
        $validated = $request->validate([
            'used_car_id' => 'numeric|required',
        ]);

        $this->repo->attachTradeIn($reserve, $validated['used_car_id']);

        return response()->json([
            'data' => UsedCarItemResource::collection($reserve->tradeins),
            'success' => 1,
            'message' => 'Скидка за автомобиль клиента учтена.'
        ]);
    }



    public function detach(WsmReserveNewCar $reserve, Request $request)
    {
        $validated = $request->validate([
            'used_car_id' => 'numeric|required',
        ]);

        $this->repo->detachTradeIn($reserve, $validated['used_car_id']);

        return response()->json([
            'data' => UsedCarItemResource::collection($reserve->tradeins),
            'success' => 1,
            'message' => 'Скидка за автомобиль клиента учтена.'
        ]);
    }



    public function index(WsmReserveNewCar $reserve, Request $request)
    {
        return response()->json([
            'data' => UsedCarItemResource::collection($reserve->tradeins),
            'success' => 1,
        ]);
    }
}
