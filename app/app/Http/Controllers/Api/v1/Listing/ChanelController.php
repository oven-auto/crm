<?php

namespace App\Http\Controllers\Api\v1\Listing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChanelController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => \App\Models\TraficChanel::with('childrens')->get(),
            'success' => 1,
        ]);
    }
}
