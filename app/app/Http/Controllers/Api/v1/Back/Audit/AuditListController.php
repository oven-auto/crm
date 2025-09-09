<?php

namespace App\Http\Controllers\Api\v1\Back\Audit;

use App\Http\Controllers\Controller;
use App\Http\Resources\Audit\AuditMasterListCollection;
use App\Repositories\Audit\AuditMasterRepository;
use Illuminate\Http\Request;

class AuditListController extends Controller
{
    public function __construct(
        private AuditMasterRepository $repo,
        public $genus = 'male',
        public $subject = 'Аудит'
    )
    {
        
    }



    /**
     * @OA\Get(
     *      path="/audits",
     *      operationId="getAuditMasterJournal",
     *      tags={"Аудит стандартов"},
     *      summary="Журнал",
     *      description="Журнал",
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/AuditMasterFilter",
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function index(Request $request)
    {
        $audits = $this->repo->paginate($request->all());

        return new AuditMasterListCollection($audits);
    }



    /**
     * @OA\Get(
     *      path="/audits/count",
     *      operationId="countAuditMasterJournal",
     *      tags={"Аудит стандартов"},
     *      summary="Счетчик журнала",
     *      description="Счетчик журнала",
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/AuditMasterFilter",
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function count(Request $request)
    {
        $res = $this->repo->count($request->all());

        return response()->json([
            'data' => $res,
            'success' => 1,
        ]);
    }
}
