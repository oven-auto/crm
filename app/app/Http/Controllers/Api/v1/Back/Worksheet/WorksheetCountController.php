<?php

namespace App\Http\Controllers\Api\v1\Back\Worksheet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WorksheetCountController extends Controller
{
    public function __invoke(Request $request, \App\Repositories\Worksheet\WorksheetRepository $repo)
    {
        $count = $repo->counter($request->all());

        return response()->json([
            'data' => $count,
            'success' => 1,
            'message' => ''
        ]);
    }
}
