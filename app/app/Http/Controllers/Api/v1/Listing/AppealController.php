<?php

namespace App\Http\Controllers\Api\v1\Listing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppealController extends Controller
{
    public function __invoke()
    {
        return response()->json([
            'data' => \App\Models\Appeal::select('id','name')->where('show',1)->get(),
            'success' => 1
        ]);
    }
}
