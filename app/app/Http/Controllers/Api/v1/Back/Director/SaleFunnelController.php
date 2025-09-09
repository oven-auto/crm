<?php

namespace App\Http\Controllers\Api\v1\Back\Director;

use App\Http\Controllers\Controller;
use App\Services\Analytic\Report\FunnelService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class SaleFunnelController extends Controller
{
    public function __construct(
        private FunnelService $service
    )
    {
        
    }



    /**
     * @OA\Get(
     *      path="/director/funnel",
     *      operationId="directorfunnel",
     *      tags={"Аналитика"},
     *      summary="Воронка продаж",
     *      description="Воронка продаж (salons = [1,2], intervals = [['01.04.2023', '31.12.2024'],['01.04.2023', '31.12.2024']])",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'intervals' => 'required|array',
            'intervals.*' => 'array',
            'intervals.*.0' => 'required',
            'salons' => 'sometimes|array'
        ]);

        $data = Arr::except($validated, ['intervals']);

        $result = $this->service->handle($request->intervals, $data);

        $result = $this->service->format($result);

        return response()->json([
            'data' => $result,
            'success' => 1
        ]);
    }
}
