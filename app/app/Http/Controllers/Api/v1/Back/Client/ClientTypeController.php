<?php

namespace App\Http\Controllers\Api\v1\Back\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientTypeController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = \App\Models\ClientType::get();

        return response()->json([
            'data' => $data,
            'success' => 1,
            'message' => 'Найдено '.$data->count().'элемента'
        ]);
    }
}
