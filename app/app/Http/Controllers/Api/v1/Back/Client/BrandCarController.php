<?php

namespace App\Http\Controllers\Api\v1\Back\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BrandCarController extends Controller
{
    public function __invoke()
    {
        $brands = \App\Models\Brand::select(['id','name'])
            ->get();

        return response()->json([
            'data' => $brands,
            'success' => 1
        ]);
    }
}
