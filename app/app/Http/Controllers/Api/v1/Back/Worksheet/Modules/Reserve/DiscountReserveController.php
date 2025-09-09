<?php

namespace App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Reserve;

use App\Classes\Notice\Notice;
use App\Http\Controllers\Controller;
use App\Http\Requests\Worksheet\Reserve\ReserveSaleListRequest;
use App\Http\Requests\Worksheet\Reserve\SaleSaveRequest;
use App\Http\Resources\Worksheet\Reserve\SaleReserveItemResource;
use App\Http\Resources\Worksheet\Reserve\SaleReserveResource;
use App\Models\Discount;
use App\Repositories\Worksheet\Modules\Reserve\ReserveDiscountRepository;

class DiscountReserveController extends Controller
{
    private $repo;

    public function __construct(ReserveDiscountRepository $repo)
    {
        $this->repo = $repo;
    }



    public function index(ReserveSaleListRequest $request)
    {
        $discounts = $this->repo->getDiscountsByReserveId($request->reserve_id);
        
        return response()->json([
            'data' => SaleReserveItemResource::collection($discounts),
            'success' => 1,
        ]);
    }



    public function store(Discount $discount, SaleSaveRequest $request)
    {
        $this->repo->save($discount, $request->validated());
        
        return (new SaleReserveResource($discount))->additional([
            'message' => 'Скидка добавлена.',
        ]);
    }



    public function show(Discount $discount)
    {
        return new SaleReserveResource($discount);
    }



    public function update(Discount $discount, SaleSaveRequest $request)
    {
        $this->repo->save($discount, $request->validated());
        
        return (new SaleReserveResource($discount))->additional([
            'message' => 'Скидка изменена.'.$request->reparation_date,
        ]);
    }



    public function destroy(Discount $discount)
    {
        $this->repo->delete($discount);

        return response()->json([
            'message' => Notice::getMessages(),
            'success' => 1,
        ]);
    }



    /**
     * Принять/отменить проверку скидки
     * @param $discount
     */
    public function check(Discount $discount)
    {
        $this->repo->check($discount);

        return (new SaleReserveResource($discount))->additional([
            'message' => Notice::getMessages(),
        ]);
    }
}
