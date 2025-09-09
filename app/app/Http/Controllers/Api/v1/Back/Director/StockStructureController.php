<?php

namespace App\Http\Controllers\Api\v1\Back\Director;

use App\Http\Controllers\Controller;
use App\Services\Analytic\Report\StockStructureService;
use Illuminate\Http\Request;

class StockStructureController extends Controller
{
    public function __construct(
        private StockStructureService $service
    )
    {
        
    }
    


    public function index(Request $request)
    { 
        $res = $this->service->handler($request->all());    

        return response()->json([
            'data' => $res,
            'success' => 1,            
        ]);
    }
}
