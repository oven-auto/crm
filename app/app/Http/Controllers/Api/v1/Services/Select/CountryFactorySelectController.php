<?php

namespace App\Http\Controllers\Api\v1\Services\Select;

use App\Http\Controllers\Controller;
use App\Models\Factory;
use Illuminate\Support\Facades\Cache;

class CountryFactorySelectController extends Controller
{
    public function index()
    {
        $result = Cache::remember('list:factory', config('cache', 'period'), function() {
            return Factory::get()->map(function($item){
                return [
                    'id' => $item->id,
                    'name' => $item->city.' ('.$item->country.')',
                ];
            });
        });

        return response()->json([
            'data' => $result,
            'success' => 1
        ]);
    }
}
