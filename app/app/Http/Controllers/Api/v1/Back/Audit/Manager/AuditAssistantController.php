<?php

namespace App\Http\Controllers\Api\v1\Back\Audit\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Audit\AuditAssistantCreateRequest;
use App\Http\Requests\Audit\AuditAssistantTraficRequest;
use App\Http\Resources\Audit\AuditAssistantItemResource;
use App\Http\Resources\Audit\AuditAssistantResource;
use App\Http\Resources\Audit\AuditAssistantTraficCollection;
use App\Repositories\Audit\AuditAssistantRepository;
use App\Repositories\Audit\DTO\AuditAssistantDTO;

class AuditAssistantController extends Controller
{
    public function __construct(
        private AuditAssistantRepository $repo,
        public $subject = 'Ассистент аудита',
        public $genus = 'male',
    )
    {
        $this->middleware('notice.message')->only(['store', 'update',]);
    }



    /**
     * @OA\Get(
     *      path="/audits/assistant",
     *      operationId="getAuditassistantList",
     *      tags={"Аудит стандартов"},
     *      summary="Список открытых ассистентов в трафике",
     *      description="Список открытых ассистентов в трафике (trafic_id = 1)",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function index(AuditAssistantTraficRequest $request)
    {
        $assists = $this->repo->getList($request->validated());

        return new AuditAssistantTraficCollection($assists);
    }



    /**
     * @OA\Get(
     *      path="/audits/assistant/{assistantId}",
     *      operationId="getAuditassistant",
     *      tags={"Аудит стандартов"},
     *      summary="Получить данные ассистента",
     *      description="Получить данные ассистента",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function show(int $id,)
    {   
        $assist = $this->repo->getById($id);

        return new AuditAssistantResource($assist);
    }



    /**
     * @OA\Post(
     *      path="/audits/assistant",
     *      operationId="postAuditassistantList",
     *      tags={"Аудит стандартов"},
     *      summary="Создать ассистента в трафике",
     *      description="Создать ассистента в трафике (trafic_id = 1, audit_id = 1, result = strjson)",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function store(AuditAssistantCreateRequest $request)
    {
        $assist = $this->repo->create(new AuditAssistantDTO($request->validated()));

        return new AuditAssistantItemResource($assist);
    }



    /**
     * @OA\Patch(
     *      path="/audits/assistant/{assistantId}",
     *      operationId="patchAuditassistantList",
     *      tags={"Аудит стандартов"},
     *      summary="Изменить ассистента в трафике",
     *      description="Изменить ассистента в трафике (trafic_id = 1, audit_id = 1, result = strjson)",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function update(int $id, AuditAssistantCreateRequest $request)
    {   
        $assist = $this->repo->update($id, new AuditAssistantDTO($request->validated()));

        return new AuditAssistantItemResource($assist);
    }
}



