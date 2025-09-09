<?php

namespace App\Http\Controllers\Api\v1\SMExpert\Deliver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function __invoke()
    {
        $brands = \App\Models\Brand::select(['id','name','slug','uid'])->get();
        return response()->json([
            'data' => $brands,
            'success' => 1,
        ]);
    }
}
