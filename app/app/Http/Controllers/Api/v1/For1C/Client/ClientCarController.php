<?php

namespace App\Http\Controllers\Api\v1\For1C\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\For1C\ClientCarFindRequest;
use App\Repositories\Client\ClientCarRepository;

class ClientCarController extends Controller
{
    public function __construct(private ClientCarRepository $repo)
    {
        
    }



    public function find(ClientCarFindRequest $request)
    {
        $cars = $this->repo->find($request->validated());

        return response()->json([
            'data' => $cars,
            'success' => 1
        ]);
    }
}
