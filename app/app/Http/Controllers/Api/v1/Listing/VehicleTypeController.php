<?php

namespace App\Http\Controllers\Api\v1\Listing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VehicleTypeController extends Controller
{
    public function __invoke()
    {
        return response()->json([
            'data' => \App\Models\VehicleType::get()->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                ];
            }),
            'success' => 1,
        ]);
    }
}
