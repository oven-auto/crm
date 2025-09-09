<?php

namespace App\Http\Controllers\Api\v1\Services\Select;

use App\Http\Controllers\Controller;

class TransmissionTypeSelectController extends Controller
{
    public function index()
    {
        $types = \App\Models\TransmissionType::get();
        return response()->json([
            'success' => 1,
            'data' => $types
        ]);
    }
}
