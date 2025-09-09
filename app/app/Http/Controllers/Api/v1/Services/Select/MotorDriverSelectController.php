<?php

namespace App\Http\Controllers\Api\v1\Services\Select;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MotorDriver;
use Illuminate\Support\Facades\Cache;

class MotorDriverSelectController extends Controller
{
    public function index()
    {
        $result = Cache::remember('list:motordriver', config('cache', 'period'), function() {
            return MotorDriver::get();
        });
        
        return response()->json([
            'data' => $result,
            'success' => 1
        ]);
    }
}
