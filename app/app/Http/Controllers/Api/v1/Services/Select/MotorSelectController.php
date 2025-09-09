<?php

namespace App\Http\Controllers\Api\v1\Services\Select;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Motor;

class MotorSelectController extends Controller
{
    public function index(Request $request)
    {
        $query = Motor::fullData();
        
        if($request->has('brand_id'))
            $query->where('brand_id', $request->get('brand_id'));

        $motors = $query->orderBy('brand_id')
            ->orderBy('power')
            ->get();

        return response()->json([
            'data' => $motors,
            'success' => 1
        ]);
    }
}
