<?php

namespace App\Http\Controllers\Api\v1\Services\Select;

use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\DealerColor;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index()
    {
        
            $result = Color::select('name', 'id')->get();

        return response()->json([
            'data' => $result,
            'success' => 1
        ]);
    }



    public function mark(Request $request)
    {
        $validated = $request->validate([
            'mark_id' => 'required'
        ]);

        //$result = \Cache::remember('list:dealercolor:'.$validated['mark_id'], config('cache', 'period'), function() use ($validated){
        $result = DealerColor::select('name', 'id')->where('mark_id', $validated['mark_id'])->get();
        //});

        return response()->json([
            'data' => $result,
            'success' => 1,
            'test' => 1
        ]);
    }
}
