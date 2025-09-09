<?php

namespace App\Http\Controllers\Api\v1\Back\Audit\CRUD;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Permissions\Audit\AuditCRUDMiddleware;
use App\Http\Requests\Audit\AuditListRequest;
use App\Http\Requests\Audit\AuditSaveRequest;
use App\Http\Resources\Audit\AuditCollection;
use App\Http\Resources\Audit\AuditEditResource;
use App\Models\Audit\Audit;
use App\Repositories\Audit\AuditRepository;

class AuditController extends Controller
{
    public function __construct(
        private AuditRepository $repo,
        public $subject = 'Аудит',
        public $genus = 'male'
    )
    {
		$this->middleware('notice.message')->only(['store', 'update', 'destroy', 'restore']);
    }



    /**
     * @OA\Get(
     *      path="/audits/audits",
     *      operationId="getAuditList",
     *      tags={"Аудит стандартов"},
     *      summary="Список аудитов",
     *      description="Список аудитов (?trash = 1, ?ids = [], ?appeals = [], ?chanels = [])",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function index(AuditListRequest $request)
    {
        $result = $this->repo->getAll($request->validated());
        
        return new AuditCollection($result);
    }

    

    /**
     * @OA\Post(
     *      path="/audits/audits",
     *      operationId="storeAuditList",
     *      tags={"Аудит стандартов"},
     *      summary="Создать аудит",
     *      description="Создать аудит (name = string, appeal_id = int, complete = int, bonus = int, malus = int, chanels = array, award = int )",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function store(AuditSaveRequest $request)
    {
        $audit = $this->repo->create($request->validated());

        return new AuditEditResource($audit);
    }



    /**
     * @OA\Patch(
     *      path="/audits/audits/{auditId}",
     *      operationId="updateAuditList",
     *      tags={"Аудит стандартов"},
     *      summary="Изменить аудит",
     *      description="Изменить аудит (name = string, appeal_id = int, complete = int, bonus = int, malus = int, chanels = array )",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function update(int $id, AuditSaveRequest $request)
    {
        $audit = $this->repo->update($id, $request->validated());

        return new AuditEditResource($audit);
    }



    /**
     * @OA\Get(
     *      path="/audits/audits/{auditId}",
     *      operationId="showAuditList",
     *      tags={"Аудит стандартов"},
     *      summary="Открыть аудит",
     *      description="Открыть аудит",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function show(int $id)
    {
        $audit = $this->repo->getById($id);

        return new AuditEditResource($audit);
    }



    /**
     * @OA\Delete(
     *      path="/audits/audits/{auditId}",
     *      operationId="delAuditList",
     *      tags={"Аудит стандартов"},
     *      summary="Удалить аудит",
     *      description="Удалить аудит",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function destroy(int $id)
    {
        $audit = $this->repo->delete($id);

        return response()->json([
            'success' => 1,
        ]);
    }



    /**
     * @OA\Put(
     *      path="/audits/audits/{auditId}/restore",
     *      operationId="restoreAuditList",
     *      tags={"Аудит стандартов"},
     *      summary="Востановить аудит",
     *      description="Востановить аудит",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ), 
     * )
     */
    public function restore(int $id)
	{
        $audit = $this->repo->restore($id);

        return response()->json([
            'success' => 1,
        ]);
    }



    /**
     * @OA\Post(
     *      path="/audits/audits/{auditId}",
     *      operationId="cloneAuditList",
     *      tags={"Аудит стандартов"},
     *      summary="Клонировать аудит",
     *      description="Клонировать аудит",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ), 
     * )
     */
    public function clone(int $id)
    {
        $audit = $this->repo->clone(id: $id);

        return new AuditEditResource($audit);
    }
}
