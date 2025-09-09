<?php

namespace App\Http\Controllers\Api\v1\Back\Client\Union;

use App\Http\Controllers\Controller;
use App\Http\Resources\Client\UnionCollection;
use App\Models\Client;
use App\Repositories\Client\ClientRepository;
use App\Repositories\Client\ClientUnionRepository;
use Illuminate\Http\Request;

class ClientUnionController extends Controller
{
    public function __construct(
        private ClientUnionRepository $repo,
        public $genus = 'female',
        public $subject = 'Связь' 
    )
    {
        $this->middleware('notice.message')->only(['store', 'destroy']);
    }



    public function show(Client $client, Request $request)
    {
        $data = $this->repo->getAllUnion($client);
        return new UnionCollection($data);
    }



    public function store(Client $client, Request $request)
    {
        $this->repo->addUnion($client, $request->get('client_id'));

        return (new UnionCollection($this->repo->getAllUnion($client)));
    }



    public function destroy(Client $client, Request $request)
    {
        $this->repo->delUnion($client, $request->get('client_id'));

        return (new UnionCollection($this->repo->getAllUnion($client)));
    }



    public function amount($client_id)
    {
        return response()->json([
            'data' => $this->repo->countUnion($client_id),
            'success' => 1,
        ]);
    }
}
