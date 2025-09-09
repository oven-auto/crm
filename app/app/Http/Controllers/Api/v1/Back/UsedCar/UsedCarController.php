<?php

namespace App\Http\Controllers\Api\v1\Back\UsedCar;

use App\Http\Controllers\Controller;
use App\Http\Resources\UsedCar\UsedCarItemResource;
use App\Repositories\UsedCar\UsedCarRepository;
use Illuminate\Http\Request;

class UsedCarController extends Controller
{
    public function __construct(
        private UsedCarRepository $repo,
        public $subject = 'Автомобиль',
        public $genus = 'male',
    )
    {
        
    }



    public function index(Request $request)
    {
        $cars = $this->repo->paginate($request->all());

        return response()->json([
            'data' => UsedCarItemResource::collection($cars),
            'success' => 1,
        ]);
    }



    public function show(int $id)
    {
        $car = $this->repo->getById($id);

        return response()->json([
            'data' => $car,
            'success' => 1,
        ]);
    }



    public function store(Request $request)
    {
        response()->json([
            'message' => 'На данный момент авто добавляется только из оценки',
            'success' => 1
        ]);
    }



    public function update(int $id, Request $request)
    {
        $car = $this->repo->update($id, $request->all());

        response()->json([
            'data' => $car,
            'success' => 1,
        ]);
    }



    public function delete(int $id)
    {
        response()->json([
            'message' => 'Пока не знаю как удалять авто, тк сделка оформлена в оценке',
            'success' => 1
        ]);
    }
}
