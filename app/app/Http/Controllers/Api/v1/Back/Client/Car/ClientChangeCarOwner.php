<?php

namespace App\Http\Controllers\Api\v1\Back\Client\Car;

use App\Http\Controllers\Controller;
use App\Models\ClientCar;
use Illuminate\Http\Request;

class ClientChangeCarOwner extends Controller
{
        /**
     * @OA\Patch(
     *      path="/client/car/{id}/owner",
     *      operationId="client_car",
     *      tags={"Клиенты"},
     *      summary="Изменить владельца авто",
     *      description="Изменить владельца авто",
     *      @OA\Parameter(
     *          name="id",
     *          description="Идентификатор мышины",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Новый владелец",
     *          @OA\JsonContent(
     *              required={"owner"},
     *              @OA\Property(property="owner", type="integer", format="integer", example="55")
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function change(ClientCar $car, Request $request)
    {
        $validated = $request->validate([
            'owner' => 'required'
        ]);

        $merged = $car->client->unionsChildren->merge($car->client->unionsParent);

        if(!$merged->contains('id', $validated['owner']))
            throw new \Exception('Новый владелец не является связанным контактом, текущего владельца. Не могу изменить владельца.');

        $car->client_id = $validated['owner'];

        $car->save();

        return response()->json([
            'success' => 1,
        ]);
    }
}
