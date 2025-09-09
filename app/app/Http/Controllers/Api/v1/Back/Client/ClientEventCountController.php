<?php

namespace App\Http\Controllers\Api\v1\Back\Client;

use App\Http\Controllers\Controller;
use App\Repositories\Client\ClientEventRepository;
use Illuminate\Http\Request;

class ClientEventCountController extends Controller
{
    public function __invoke(Request $request, ClientEventRepository $repo)
    {
        $count = $repo->counter($request->input());
        return response()->json([
            'data' => $count,
            'success' => 1
        ]);
    }
}
