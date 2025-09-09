<?php

namespace App\Http\Controllers\Api\v1\Listing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StructureController extends Controller
{
    public function __invoke()
    {
        return response()->json([
            'data' => \App\Models\Structure::get(),
            'success' => 1
        ]);
    }
}
