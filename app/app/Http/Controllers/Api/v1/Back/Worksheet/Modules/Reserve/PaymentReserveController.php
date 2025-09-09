<?php

namespace App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Reserve;

use App\Http\Controllers\Controller;
use App\Http\Requests\Worksheet\Reserve\ReservePaymentSaveRequest;
use App\Http\Resources\Worksheet\Reserve\PaymentSaveResource;
use App\Models\WsmReservePayment;
use App\Repositories\Worksheet\Modules\Reserve\ReservePaymentRepository;

class PaymentReserveController extends Controller
{
    private $repo;

    public function __construct(ReservePaymentRepository $repo)
    {
        $this->repo = $repo;
    }



    public function store(WsmReservePayment $pay, ReservePaymentSaveRequest $request)
    {
        $this->repo->save($pay, $request->validated());

        return response()->json([
            'data' => new PaymentSaveResource($pay),
            'success' => 1,
            'message' => 'Оплата зафиксирована.'
        ]);
    }



    public function destroy(WsmReservePayment $pay)
    {
        $this->repo->delete($pay);

        return response()->json([
            'message' => 'Оплата отменена.',
            'success' => 1,
        ]);
    }



    public function update(WsmReservePayment $pay, ReservePaymentSaveRequest $request)
    {
        $this->repo->save($pay, $request->validated());

        return response()->json([
            'message' => 'Оплата зафиксирована.',
            'success' => 1,
        ]);
    }
}
