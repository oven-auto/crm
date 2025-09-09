<?php

namespace App\Http\Controllers\Api\v1\Back\Client;

use App\Http\Controllers\Controller;
use \App\Models\Client;
use App\Http\Requests\Client\ClientCarRequest;
use App\Http\Resources\Client\Car\ClientCarCollection;
use App\Http\Resources\Client\Car\ClientCarEditResource;
use \App\Models\ClientCar;
use App\Services\Comment\Comment;
use \App\Repositories\Client\ClientCarRepository;
use Illuminate\Http\JsonResponse;

class ClientCarController extends Controller
{
    public function __construct(
        private ClientCarRepository $repo,
        public $genus = 'male',
        public $subject = 'Автомобиль клиента' 
    )
    {
        $this->middleware('notice.message')->only(['store', 'destroy', 'update', ]);
    }

   

    /**
     * @OA\Get(
     *  path="/client/car/list/{clientId}",
     *  tags={"Автомобиль клиента"},
     *  operationId="getClientCarList",
     *  summary="Список автомобиль клиента",
     *  description="Список автомобиль клиента",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     */
    public function index(Client $client) : ClientCarCollection
    {
        return new ClientCarCollection($client->cars);
    }

    
    
    /**
     * @OA\Post(
     *  path="/client/car/{clientId}",
     *  tags={"Автомобиль клиента"},
     *  operationId="storeClientCarList",
     *  summary="Добавить автомобиль клиента",
     *  description="Добавить автомобиль клиента",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     */
    public function store(Client $client, ClientCarRequest $request) : ClientCarCollection
    {
        $clientcar = $this->repo->store($client, $request->input());

        Comment::add($clientcar, 'create');

        return (new ClientCarCollection($client->cars));
    }

   
    
    /**
     * @OA\Patch(
     *  path="/client/car/{carId}",
     *  tags={"Автомобиль клиента"},
     *  operationId="updateClientCarList",
     *  summary="Изменить автомобиль клиента",
     *  description="Изменить автомобиль клиента",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     */
    public function update(ClientCar $car, ClientCarRequest $request) : ClientCarCollection
    {
        $this->repo->update($car, $request->input());

        Comment::add($car, 'update');

        return (new ClientCarCollection($car->client->cars));
    }

    
    
    /**
     * @OA\Delete(
     *  path="/client/car/{carId}",
     *  tags={"Автомобиль клиента"},
     *  operationId="deleteClientCarList",
     *  summary="Удалить автомобиль клиента",
     *  description="Удалить автомобиль клиента",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     */
    public function destroy(ClientCar $car) : ClientCarCollection
    {
        Comment::add($car, 'delete');

        $this->repo->hide($car);

        return (new ClientCarCollection($car->client->cars));
    }

   
    
    /**
     * @OA\Get(
     *  path="/client/car/amount/{clientId}",
     *  tags={"Количество Автомобиль клиента"},
     *  operationId="amountClientCarList",
     *  summary="Количество автомобиль клиента",
     *  description="Количество автомобиль клиента",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     */
    public function amount(Client $client) : JsonResponse
    {
        return response()->json([
            'data' => $this->repo->amountClientCar($client),
            'success' => 1
        ]);
    }

    
    
    public function show(ClientCar $car) : ClientCarEditResource
    {
        return new ClientCarEditResource($car);
    }
}
