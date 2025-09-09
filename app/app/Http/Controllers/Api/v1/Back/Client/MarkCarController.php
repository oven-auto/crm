<?php

namespace App\Http\Controllers\Api\v1\Back\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class MarkCarController extends Controller
{
    public function __invoke(\App\Models\Brand $brand)
    {
        $marks = \App\Models\Mark::select(['id', DB::raw('CONCAT (IFNULL(CONCAT(prefix," "), ""), name) as name')])
            ->where('brand_id',$brand->id)
            ->get();

        return response()->json([
            'data' => $marks,
            'success' => 1
        ]);
    }
}
