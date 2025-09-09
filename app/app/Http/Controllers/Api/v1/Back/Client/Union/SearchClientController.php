<?php

namespace App\Http\Controllers\Api\v1\Back\Client\Union;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Repositories\Client\ClientRepository;
use Illuminate\Http\Request;
use App\Helpers\String\StringHelper;

class SearchClientController extends Controller
{
    public function search(Request $request, ClientRepository $repo)
    {
        $clients = collect();
        $inWorksheet = collect();

        if ($request->has('car_id')) {
            $car = Car::find($request->car_id);
            if ($car && $car->isReserved()) {
                $worksheetClient = $car->reserve->worksheet->client;
                $subClients = $car->reserve->worksheet->subclients;
                $subClients = $subClients->push($worksheetClient);
                $inWorksheet = $subClients;
            }
        }

        $arrW = $inWorksheet->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->full_name,
                'worksheet' => 1,
                'attribute' => $item->client_type_id == 1 ? $item->phones->map(function ($item) {
                return StringHelper::phoneMask($item->phone);
                }) : [$item->inn->number],
            ];
        });

        $arrC = $clients->merge($repo->get($request->input(), 15))->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->full_name,
                'worksheet' => 0,
                'attribute' => $item->client_type_id == 1 ? $item->phones->map(function ($item) {
                    return StringHelper::phoneMask($item->phone);
                }) : [$item->inn->number],
            ];
        });

        $arr = $arrW->merge($arrC);

        return response()->json([
            'data' => $arr,
            'success' => 1
        ]);
    }
}
