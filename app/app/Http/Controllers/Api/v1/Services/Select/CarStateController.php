<?php

namespace App\Http\Controllers\Api\v1\Services\Select;

use App\Http\Controllers\Controller;
use App\Models\CarState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CarStateController extends Controller
{
    public function list()
    {
        $result = Cache::remember('list:carstate', config('cache', 'period'), function() {
            return CarState::select('description', 'status')->get();
        });
        
        return response()->json([
            'data' => $result,
            'success' => 1,
        ]);
    }
}
