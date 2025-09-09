<?php

namespace App\Http\Controllers\Api\v1\Back\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventStatusController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->json([
            'data' => \App\Models\ClientEventStatusDescription::get(),
            'success' => 1
        ]);
    }
}
