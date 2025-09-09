<?php

namespace App\Http\Controllers\Api\v1\Back\Car;

use App\Http\Controllers\Controller;
use App\Models\Car;

class CarImageController extends Controller
{
    public function delete(Car $car)
    {
        $car->image()->detach();

        return response()->json([
            'success' => 1,
        ]);
    }
}
