<?php

namespace App\Http\Controllers\Api\v1\Back\Client;

use App\Http\Controllers\Controller;
use App\Repositories\Client\ClientRepository;
use App\Http\Resources\Client\ClientListCollection;
use App\Http\Resources\Client\ClientEditResource;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Requests\Client\ClientStoreRequest;
use App\Http\Resources\Client\ClientCartResource;
use App\Services\Comment\Comment;

class ClientController extends Controller
{
    public function __construct(
        private ClientRepository $repo,
        public $genus = 'male',
        public $subject = 'Клиент'    
    )
    {
        $this->middleware('notice.message')->only(['store', 'update', 'destroy']);
    }


    
    /**
     * @OA\Get(
     *  path="/client/list",
     *  tags={"Клиент"},
     *  operationId="getClientList",
     *  summary="Список клиентов",
     *  description="Список клиентов (
     *      параметры для фильтрации: lastname, firstname, fathername, phone, email, client_type_id, trafic_sex_id
     *      trafic_zone_id, has_worksheet, input, register_interval, register_start, register_end, action_interval, 
     *      action_start, action_end, ids, personal
     *  )",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     */
    public function index(Request $request) : ClientListCollection
    {
        $clients = $this->repo->paginate($request->input(), 30);

        return new ClientListCollection($clients);
    }



    /**
     * @OA\Get(
     *  path="/client/show/{clientId}",
     *  tags={"Клиент"},
     *  operationId="showClient",
     *  summary="Предпоказ клиента",
     *  description="Предпоказ клиента",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     */
    public function show(Client $client)
    {
        return new ClientCartResource($client);
    }



    /**
     * @OA\Get(
     *  path="/client/{clientId}",
     *  tags={"Клиент"},
     *  operationId="editClient",
     *  summary="Открыть клиента",
     *  description="Открыть клиента",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     */
    public function edit($client) : ClientEditResource
    {
        $client = Client::query()->filesCount()->linksCount()->find($client);

        Comment::add($client, 'show');

        return new ClientEditResource($client);
    }



    /**
     * @OA\Post(
     *      path="/client/create",
     *      operationId="storeClient",
     *      tags={"Клиент"},
     *      summary="Создать клиента",
     *      description="Создать клиента (
     *          firstname,lastname,fathername,client_type_id,trafic_sex_id,trafic_zone_id,birthday_at,driver_license_issue_at,
     *          passport_issue_at,address,driving_license,serial_number,form_owner_id,phones,emails,url,inn,company_name,
     *      )",
     *      
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function store(Client $client, ClientStoreRequest $request) : ClientEditResource
    {
        $this->repo->save($client, $request->getDTO());
        
        Comment::add($client, 'create');

        return (new ClientEditResource($client));
    }



    /**
     * @OA\Patch(
     *      path="/client/{clientId}",
     *      operationId="updateClient",
     *      tags={"Клиент"},
     *      summary="Изменить клиента",
     *      description="Изменить клиента (
     *          firstname,lastname,fathername,client_type_id,trafic_sex_id,trafic_zone_id,birthday_at,driver_license_issue_at,
     *          passport_issue_at,address,driving_license,serial_number,form_owner_id,phones,emails,url,inn,company_name,
     *      )",
     *      
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function update(Client $client, ClientStoreRequest $request) : ClientEditResource
    {
        $this->repo->save($client, $request->getDTO());

        Comment::add($client, 'update');

        return (new ClientEditResource($client));
    }


    
    /**
     * @OA\Delete(
     *      path="/client/{clientId}",
     *      operationId="deleteClient",
     *      tags={"Клиент"},
     *      summary="Удалить клиента",
     *      description="Удалить клиента",
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function destroy(Client $client) : ClientEditResource
    {
        $this->repo->delete($client);

        Comment::add($client, 'delete');

        return (new ClientEditResource($client))->additional(['result' => 1]);
    }
}
