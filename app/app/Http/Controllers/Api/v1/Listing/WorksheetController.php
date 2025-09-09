<?php

namespace App\Http\Controllers\Api\v1\Listing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WorksheetController extends Controller
{
    public function __invoke()
    {
        $result = \App\Models\WorksheetStatus::get();
        return response()->json([
            'data' => $result,
            'success' => 1
        ]);
    }
}
