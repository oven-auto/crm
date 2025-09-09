<?php

namespace App\Http\Controllers\Api\v1\Listing;

use App\Http\Controllers\Controller;
use App\Models\CarSaleSign;
use Illuminate\Http\Request;

class SaleSignController extends Controller
{
    public function index()
    {
        $res = CarSaleSign::get();
        return response()->json([
            'data' => $res,
            'success' => 1,
        ]);
    }
}
