<?php

namespace App\Http\Controllers\Api\v1\For1C\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\For1C\ClientFindRequest;
use App\Http\Resources\For1c\ClientFindResource;
use App\Repositories\Client\ClientRepository;

class ClientController extends Controller
{
    public function __construct(
        private ClientRepository $repo,
    )
    {
        
    }



    public function find(ClientFindRequest $request)
    {
        $client = $this->repo->find($request->validated());

        return new ClientFindResource($client);
    }
}
