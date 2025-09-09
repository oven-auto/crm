<?php

namespace App\Http\Controllers\Api\v1\Back\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BodyCarController extends Controller
{
    public function __invoke()
    {
        $bodies = \App\Models\BodyWork::select(['id','name'])
            ->where('diller',0)
            ->get();

        return response()->json([
            'data' => $bodies,
            'success' => 1
        ]);
    }
}
