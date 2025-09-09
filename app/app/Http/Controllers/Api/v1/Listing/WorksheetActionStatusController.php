<?php

namespace App\Http\Controllers\Api\v1\Listing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WorksheetActionStatusController extends Controller
{
    public function __invoke()
    {
        return response()->json([
            'data' => \App\Models\WorksheetAction::getStatuses(),
            'success' => 1
        ]);
    }
}
