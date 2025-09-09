<?php

namespace App\Http\Controllers\Api\v1\Back\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Client\ClientEventRequest;
use App\Http\Resources\Client\EventIndexCollection;
use App\Http\Resources\Client\EventSaveResource;
use App\Repositories\Client\ClientEventRepository;

class ClientEventController extends Controller
{
    public function __construct(
        private ClientEventRepository $repo,
        public $genus = 'female',
        public $subject = 'Коммуникация',
    )
    {
        $this->middleware('permission.clientevent:index')->only('index');
        $this->middleware('permission.clientevent:update')->only('update');
        $this->middleware('permission.clientevent:show')->only('show');
        $this->middleware('permission.clientevent:store' )->only('store');

        $this->middleware('notice.message')->only(['store', 'update', ]);
    }

    

    public function index(Request $request)
    {
        $data = $this->repo->paginate($request->input(), 20);

        return (new EventIndexCollection($data));
    }



    public function show($id)
    {
        $clientEventStatus = $this->repo->getStatusById($id);

        return (new EventSaveResource($clientEventStatus));
    }



    public function store(ClientEventRequest $request)
    {
        $eventstatus = $this->repo->create($request->getDTO());

        return (new EventSaveResource($eventstatus));
    }



    public function update(int $statusId, ClientEventRequest $request)
    {

        $eventstatus = $this->repo->update($statusId, $request->getDTO());

        return (new EventSaveResource($eventstatus));
    }
}
