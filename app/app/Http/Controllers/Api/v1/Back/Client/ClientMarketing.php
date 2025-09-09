<?php

namespace App\Http\Controllers\Api\v1\Back\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Client\EventListCollection;
use App\Repositories\Client\ClientEventRepository;

class ClientMarketing extends Controller
{
    private $repo;
    public function __construct(ClientEventRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(Request $request)
    {
        if(!$request->has('client_id')) //лень реквест делать
            throw new \Exception('Не указан клиент');
            
        $data = $this->repo->getAllInGroupByClientId($request->get('client_id'));

        return new EventListCollection($data);
    }
}
