<?php

namespace App\Http\Controllers\Api\v1\SMExpert\Deliver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MarkController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = \App\Models\Mark::with('brand')->select(['id','name','slug','uid','brand_id']);
        if($request->has('brand'))
            $query->where('brand_id', $request->get('brand'));
        $marks = $query->get();
        return response()->json([
            'success' => 1,
            'data' => $marks->map(function($item){
                return [
                    'uid' => $item->uid,
                    'name' => $item->name,
                    'brand_id' => $item->brand->uid
                ];
            })
        ]);
    }
}
