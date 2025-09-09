<?php

namespace App\Http\Controllers\Api\v1\Back\Client;

use App\Http\Controllers\Controller;
use App\Services\Client\CheckClient;
use Illuminate\Http\Request;

class CheckClientController extends Controller
{
    public function check(Request $request)
    {
        $data = CheckClient::check($request->all());

        return response()->json([
            'success' => 1,
            'data' => $data,
            'result' => $data['client'] ? 1 : 0,
        ]);
    }
}
