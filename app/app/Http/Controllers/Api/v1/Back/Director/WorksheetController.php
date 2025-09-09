<?php

namespace App\Http\Controllers\Api\v1\Back\Director;

use App\Http\Controllers\Controller;
use App\Http\Requests\Director\DirectorRequest;
use App\Models\Structure;
use App\Repositories\Worksheet\WorksheetRepository;
use App\Services\Analytic\NewWorksheet\WorksheetAnalytic;
use App\Services\Analytic\Worksheet\AnalyticWorksheet;
use Illuminate\Http\Request;
use App\Services\Analytic\Worksheet\WorksheetAuthor;
use App\Services\Analytic\Worksheet\ResultWorksheetAnalytic;
use App\Services\Analytic\Worksheet\ClosedWorksheetAnalytic;
use App\Services\Analytic\Worksheet\CreatedWorksheetAnalytic;

class WorksheetController extends Controller
{
    // public function test(Request $request, WorksheetRepository $repo, AnalyticWorksheet $analytic)
    // {
    //     $structureIds = Structure::select('structures.*')
    //         ->leftJoin('company_structures', 'company_structures.structure_id', 'structures.id')
    //         ->whereIn('company_structures.id', $request->structure_ids)
    //         ->pluck('id')
    //         ->toArray();

    //     $request->request->remove('structure_ids');

    //     $request->merge(['structure_ids' => $structureIds]);

    //     $nonIntervalArray = $request->except([
    //         'interval_begin', 'interval_end',
    //         'second_interval_begin', 'second_interval_end',
    //         'third_interval_begin', 'third_interval_end',
    //     ]);

    //     return response()->json([
    //         'data' => [
    //             'author'    => WorksheetAuthor::getCountAnalyticByAuthor($nonIntervalArray),
    //             'created'   => $analytic->fasade($request->all(), new CreatedWorksheetAnalytic()),
    //             'closed'    => $analytic->fasade($request->all(), new ClosedWorksheetAnalytic()),
    //             'results'   => $analytic->fasade($request->all(), new ResultWorksheetAnalytic()),
    //             'work'      => WorksheetAuthor::getCount($nonIntervalArray),
    //         ],
    //         'success' => 1,
    //     ]);
    // }



    /**
     * @OA\Get(
     *      path="/director/worksheets",
     *      operationId="directorWorksheet",
     *      tags={"Аналитика"},
     *      summary="По рабочим листам",
     *      description="По рабочим листам",
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
    public function __invoke(DirectorRequest $request, WorksheetAnalytic $service)
    {
        $data = $request->except(['intervals','structure_ids']);

        $intervals = $request->only('intervals')['intervals'];
        
        $res = $service->handle($intervals, $data,);
        
        return response()->json([
            'data' => [
                'created'   => $res['created'] ?? [],
                'author'    => $res['author'] ?? [],                
                'closed'    => $res['closed'] ?? [],
                'results'   => $res['close_status'] ?? [],
                'work'      => $res['worked'] ?? [],
            ],
            'success' => 1,
        ]);
    }
}
