<?php

namespace App\Http\Controllers\Api\v1\Back\Director;

use App\Http\Controllers\Controller;
use App\Repositories\Trafic\TraficRepository;
use App\Services\Analytic\NewTrafic\TraficAnalytic;
use Illuminate\Http\Request;
use App\Services\Analytic\Trafic\AnalyticTrafic;
use App\Services\Analytic\Trafic\TargetTotalTraficAnalytic;
use App\Services\Analytic\Trafic\TargetTraficAnalytic;
use App\Services\Analytic\Trafic\TotalTraficAnalytic;
use App\Services\Analytic\Trafic\DeleteTraficAnalytic;
use App\Services\Analytic\Trafic\GeneralTraficAnalytic;
use App\Services\Analytic\Trafic\AuthorTraficAnalytic;
use App\Services\Analytic\Trafic\PersonalTraficAnalytic;
use App\Services\Analytic\Trafic\ChanelTraficAnalytic;

class TraficController extends Controller
{
    // public function test(Request $request, AnalyticTrafic $analytic)
    // {
    //     return response()->json([

    //         'data' => [
    //             'target_total'  => $analytic->analytics($request->all(), new TargetTotalTraficAnalytic()),
                
    //             'target'    	=> $analytic->analytics($request->all(), new TargetTraficAnalytic()),
                
    //             'total'     	=> $analytic->analytics($request->all(), new TotalTraficAnalytic()),

    //             'deleted'   	=> $analytic->analytics($request->all(), new DeleteTraficAnalytic()),

    //             'general'   	=> $analytic->analytics($request->all(), new GeneralTraficAnalytic()),
                
    //             'author'    	=> $analytic->analytics($request->all(), new AuthorTraficAnalytic()),
                
    //             'personal'  	=> $analytic->analytics($request->all(), new PersonalTraficAnalytic()),
                
    //             'chanel'    	=> $analytic->analytics($request->all(), new ChanelTraficAnalytic()),                              
    //         ],

    //         'success' => 1,
    //     ]);
    // }



    /**
     * @OA\Get(
     *      path="/director/trafics",
     *      operationId="directorTrafic",
     *      tags={"Аналитика"},
     *      summary="По трафику",
     *      description="По трафику",
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/DirectorRequest",
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function __invoke(request $request, TraficAnalytic $service)
    {
        $data = $request->except(['intervals','structure_ids']);
        
        $intervals = $request->only('intervals')['intervals'];
        
        $res = $service->handle($intervals, $data,);
        
        return response()->json([
            'data' => [
                'total'         => $res['total']        ?? [],
                'general'    	=> $res['client_type']  ?? [],
                'author'        => $res['author']       ?? [],
                'personal'      => $res['manager']      ?? [],
                'chanel'        => $res['chanel']       ?? [],
                'target_total'  => $res['target_total'] ?? [],
                'target'        => $res['status']       ?? [],
                'deleted'       => $res['deleted']      ?? [],
                'appeal'        => $res['appeal']       ?? [],
            ],
            'success' => 1
        ]);
    }
}
