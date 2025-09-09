<?php

namespace App\Http\Controllers\Api\v1\Services\Select;

use App\Http\Controllers\Controller;
use App\Models\MotorToxic;

class MotorToxicController extends Controller
{
    public function index()
    {
        $toxic = MotorToxic::select('name', 'id')->get();
        return response()->json([
            'data' => $toxic,
            'success' => 1
        ]);
    }
}
