<?php

namespace App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Credit;

use App\Http\Controllers\Controller;
use App\Http\Requests\Worksheet\Credit\CreditCreateRequest;
use App\Http\Requests\Worksheet\Credit\CreditWorksheetRequest;
use App\Http\Resources\Default\SuccessResource;
use App\Http\Resources\Worksheet\Credit\CreditCollection;
use App\Http\Resources\Worksheet\Credit\CreditItemResource;
use App\Repositories\Worksheet\Modules\Credit\CreditRepository;

class CreditController extends Controller
{
    public function __construct(
        private CreditRepository $repo,
        public $genus = 'male',
        public $subject = 'Кредит',
    )
    {
        $this->middleware('notice.message')->only(['store', 'update', 'destroy', 'restore']);
    }


    
    /**
     * @OA\Get(
     *      path="/worksheet/modules/credits",
     *      operationId="creditList",
     *      tags={"МОДУЛЬ РЛ: Кредит"},
     *      summary="Список для рабочего листа (worksheet_id = int) ",
     *      description="Список для рабочего листа ",
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function index(CreditWorksheetRequest $request)
    {
        $credits = $this->repo->getToWorksheet($request->validated());

        return new CreditCollection($credits);
    }



    /**
     * @OA\Post(
     *      path="/worksheet/modules/credits",
     *      operationId="creditPost",
     *      tags={"МОДУЛЬ РЛ: Кредит"},
     *      summary="Создать",
     *      description="Создать",
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/CreditCreateRequest",
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function store(CreditCreateRequest $request)
    {
        $credit = $this->repo->create($request->getDTO());

        return new CreditItemResource($credit);
    }



    /**
     * @OA\Patch(
     *      path="/worksheet/modules/credits/{id}",
     *      operationId="creditPatch",
     *      tags={"МОДУЛЬ РЛ: Кредит"},
     *      summary="Изменить",
     *      description="Изменить",
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/CreditCreateRequest",
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function update(int $id, CreditCreateRequest $request)
    {
        $credit = $this->repo->update($id, $request->getDTO());

        return new CreditItemResource($credit);
    }



    /**
     * @OA\Get(
     *      path="/worksheet/modules/credits/{id}",
     *      operationId="creditShow",
     *      tags={"МОДУЛЬ РЛ: Кредит"},
     *      summary="Открыть",
     *      description="Открыть",
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function show(int $id)
    {
        $credit = $this->repo->getById($id);

        return new CreditItemResource($credit);
    }



    /**
     * @OA\Delete(
     *      path="/worksheet/modules/credits/{id}",
     *      operationId="creditDelete",
     *      tags={"МОДУЛЬ РЛ: Кредит"},
     *      summary="Удалить",
     *      description="Удалить",
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function destroy(int $id)
    {
        $this->repo->delete($id);

        return new SuccessResource(1);
    }
}
