<?php

namespace App\Http\Controllers\Api\v1\Back\Service;

use App\Http\Controllers\Controller;
use App\Models\ServicePayment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => ServicePayment::get(),
            'success' => 1,
        ]);
    }
}
