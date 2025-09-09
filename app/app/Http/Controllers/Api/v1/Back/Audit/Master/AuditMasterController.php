<?php

namespace App\Http\Controllers\Api\v1\Back\Audit\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Audit\AuditCheckPointRequest;
use App\Http\Requests\Audit\AuditInTraficRequest;
use App\Http\Requests\Audit\AuditMasterRequest;
use App\Http\Resources\Audit\AuditMasterItemResource;
use App\Http\Resources\Audit\AuditMasterTraficCollection;
use App\Http\Resources\Default\SuccessResource;
use App\Repositories\Audit\AuditMasterRepository;
use App\Repositories\Audit\DTO\AuditMasterDTO;

class AuditMasterController extends Controller
{
    public function __construct(
        private AuditMasterRepository $repo,
        public $genus = 'male',
        public $subject = 'Аудит'
    )
    {
        $this->middleware('notice.message')->only(['store', 'update', 'restore', 'destroy']);
        $this->middleware(\App\Http\Middleware\Audit\AuditArbitrMiddleware::class)->only('arbitr');
        $this->middleware(\App\Http\Middleware\Audit\AuditDeleteMiddleware::class)->only('destroy');
        $this->middleware(\App\Http\Middleware\Audit\AuditCreateMiddleware::class)->only('create');
        $this->middleware(\App\Http\Middleware\Audit\AuditRestoreMiddleware::class)->only('restore');
        $this->middleware(\App\Http\Middleware\Audit\AuditUpdateMiddleware::class)->only('update');
        $this->middleware(\App\Http\Middleware\Audit\AuditShowMiddleware::class)->only('show');
    }



    /**
     * @OA\Get(
     *      path="/audits/master",
     *      operationId="getAuditMasterList",
     *      tags={"Аудит стандартов"},
     *      summary="Список открытых для проверки аудитов в трафике",
     *      description="Список открытых для проверки аудитов в трафике (trafic_id = 1)",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function index(AuditInTraficRequest $request)
    {
        $masters = $this->repo->getAll(data: $request->validated());

        return new AuditMasterTraficCollection($masters);
    }



    /**
     * @OA\Get(
     *      path="/audits/master/{masterId}",
     *      operationId="getAuditMaster",
     *      tags={"Аудит стандартов"},
     *      summary="Получить данные открытой проверки аудита",
     *      description="Получить данные открытой проверки аудита",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function show(int $id)
    {
        $audit = $this->repo->getById(id: $id);
        
        return new AuditMasterItemResource($audit);
    }



    /**
     * @OA\Post(
     *      path="/audits/master}",
     *      operationId="postAuditMaster",
     *      tags={"Аудит стандартов"},
     *      summary="Создать данные открытой проверки аудита",
     *      description="Создать данные открытой проверки аудита 
     *      (trafic_id, audit_id, result = {positive = [questionId, ..], negative = [questionId, ..], neutral = [questionId, ..]})",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function store(AuditMasterRequest $request)
    {
        $result = $this->repo->create(dto: new AuditMasterDTO($request->validated()));

        return response()->json([
            'data' => [
                'id' => $result->id,
            ],
            'success' => 1,
        ]);
    }



    /**
     * @OA\Patch(
     *      path="/audits/master/{masterId}",
     *      operationId="patchAuditMaster",
     *      tags={"Аудит стандартов"},
     *      summary="Изменить данные открытой проверки аудита",
     *      description="Изменить данные открытой проверки аудита 
     *      (trafic_id, audit_id, result = {positive = [questionId, ..], negative = [questionId, ..], neutral = [questionId, ..]})",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function update(int $id, AuditMasterRequest $request)
    {
        $this->repo->update(id: $id, dto: new AuditMasterDTO($request->validated()));

        return new SuccessResource([]);
    }



    /**
     * @OA\Get(
     *      path="/audits/master/check",
     *      operationId="getAuditMasterCheck",
     *      tags={"Аудит стандартов"},
     *      summary="Проверить кол-во балов в аудите",
     *      description="Проверить кол-во балов в аудите
     *      result = {positive = [questionId, ..], negative = [questionId, ..], neutral = [questionId, ..]}",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function check(AuditCheckPointRequest $request)
    {
        $calc = $this->repo->calcPoint($request->validated());

        return response()->json([
            'data' => [
                'positive'  => $calc->getPositive(),
                'negative'  => $calc->getNegative(),
                'neutral'   => $calc->getNeutral(),
                'point'     => $calc->getResult(),
                'total'     => $calc->getTotal(),
            ],
            'success' => 1,
        ]);
    }



    /**
     * @OA\Patch(
     *      path="/audits/master/{auditID}/arbitr",
     *      operationId="patchAuditMasterArbitr",
     *      tags={"Аудит стандартов"},
     *      summary="Перевести мастер-аудит в состояние аппеляция.",
     *      description="Перевести мастер-аудит в состояние аппеляция.",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function arbitr(int $id)
    {
        $this->repo->arbitr($id);

        return new SuccessResource([]);
    }



    /**
     * @OA\Delete(
     *      path="/audits/master/{auditID}",
     *      operationId="delAuditMaster",
     *      tags={"Аудит стандартов"},
     *      summary="Удалить мастер-аудит .",
     *      description="Удалить мастер-аудит .",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function destroy(int $id)
    {
        $this->repo->delete($id);

        return new SuccessResource([]);
    }



    /**
     * @OA\Putch(
     *      path="/audits/master/{auditID}/restore",
     *      operationId="restAuditMaster",
     *      tags={"Аудит стандартов"},
     *      summary="Востановить мастер-аудит .",
     *      description="Востановить мастер-аудит .",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function restore(int $id)
    {
        $this->repo->restore($id);

        return new SuccessResource([]);
    }
}
