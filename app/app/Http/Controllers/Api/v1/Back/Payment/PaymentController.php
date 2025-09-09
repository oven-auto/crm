<?php

namespace App\Http\Controllers\Api\v1\Back\Payment;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function update(Payment $payment, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required'
        ]);

        $payment->fill($validated)->save();

        return response()->json([
            'id' => $payment->id,
            'name' => $payment->name
        ]);
    }
}
