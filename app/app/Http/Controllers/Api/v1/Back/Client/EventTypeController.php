<?php

namespace App\Http\Controllers\Api\v1\Back\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventTypeController extends Controller
{
    public function __invoke()
    {
        $data = \App\Models\EventType::select('id','name')->orderBy('sort')->get();

        return response()->json([
            'data' => $data,
            'success' => 1
        ]);
    }
}
