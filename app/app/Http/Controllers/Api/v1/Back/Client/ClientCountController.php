<?php

namespace App\Http\Controllers\Api\v1\Back\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientCountController extends Controller
{
    public function __invoke(\App\Repositories\Client\ClientRepository $repo, Request $request)
    {
        $count = $repo->counter($request->all());

        return response()->json([
            'data' => $count,
            'success' => 1,
            'message' => ''
        ]);
    }
}
