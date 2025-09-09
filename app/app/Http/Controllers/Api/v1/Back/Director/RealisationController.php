<?php

namespace App\Http\Controllers\Api\v1\Back\Director;

use App\Http\Controllers\Controller;
use App\Services\Analytic\Report\RealisationService;
use Illuminate\Http\Request;

class RealisationController extends Controller
{
    public function __construct(
        private RealisationService $service
    )
    {
        
    }



    public function index(Request $request)
    {  
        $validated = $request->validate([
            'intervals' => 'required|array',
            'intervals.*' => 'array',
            'intervals.0' => 'required',
            'salons' => 'sometimes|array'
        ]);

        $res = $this->service->getData($validated);

        return response()->json([
            'data' => $res,
            'success' => 1,
        ]);
    }
}
