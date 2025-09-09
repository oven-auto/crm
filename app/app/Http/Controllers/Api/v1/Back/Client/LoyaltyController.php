<?php

namespace App\Http\Controllers\Api\v1\Back\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loyalty;

class LoyaltyController extends Controller
{
    public function list()
    {
        return response()->json([
            'data' => Loyalty::get(),
            'success' => 1
        ]);
    }
}
