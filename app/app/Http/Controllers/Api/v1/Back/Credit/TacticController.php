<?php

namespace App\Http\Controllers\Api\v1\Back\Credit;

use App\Http\Controllers\Controller;
use App\Http\Requests\Credit\Tactic\CreateTacticRequest;
use App\Repositories\Credit\Tactic\CreditTacticRepository;
use App\Http\Requests\Credit\Tactic\ListTacticRequest;
use App\Http\Resources\Credit\Tactic\TacticCollection;
use App\Http\Resources\Credit\Tactic\TacticItemResource;
use App\Http\Resources\Default\SuccessResource;

class TacticController extends Controller
{
    public function __construct(
        private CreditTacticRepository $repo,
        public $subject = 'Тактика',
        public $genus = 'female',
    )
    {
        $this->middleware('notice.message')->only(['store', 'update', 'destroy', 'restore']);
    }

    /**
     * @OA\Get(
     *      path="/creditlist/tactics",
     *      operationId="getTacticCredit",
     *      tags={"Тактика кредита"},
     *      summary="Список тактик",
     *      description="Список тактик",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function index(ListTacticRequest $request)
    {
        $tactics = $this->repo->get($request->all());

        return new TacticCollection($tactics);
    }



    /**
     * @OA\Post(
     *      path="/creditlist/tactics",
     *      operationId="postTacticCredit",
     *      tags={"Тактика кредита"},
     *      summary="Создать тактику",
     *      description="Создать тактику (name)",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function store(CreateTacticRequest $request)
    {
        $tactic = $this->repo->create($request->getDTO());

        return new TacticItemResource($tactic);
    }



    /**
     * @OA\Patch(
     *      path="/creditlist/tactics/{id}",
     *      operationId="patchTacticCredit",
     *      tags={"Тактика кредита"},
     *      summary="Изменить тактику",
     *      description="Изменить тактику (name)",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function update(int $id, CreateTacticRequest $request)
    {
        $tactic = $this->repo->update($id, $request->getDTO());

        return new TacticItemResource($tactic);
    }



    /**
     * @OA\Get(
     *      path="/creditlist/tactics/{id}",
     *      operationId="showTacticCredit",
     *      tags={"Тактика кредита"},
     *      summary="Открыть тактику",
     *      description="Открыть тактику",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function show(int $id)
    {
        $tactic = $this->repo->getById($id);

        return new TacticItemResource($tactic);

    }



    /**
     * @OA\Delete(
     *      path="/creditlist/tactics/{id}",
     *      operationId="delTacticCredit",
     *      tags={"Тактика кредита"},
     *      summary="Удалить тактику",
     *      description="Удалить тактику",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function destroy(int $id)
    {
        $this->repo->delete($id);

        return new SuccessResource(1);
    }



    /**
     * @OA\Patch(
     *      path="/creditlist/tactics/{id}/restore",
     *      operationId="restTacticCredit",
     *      tags={"Тактика кредита"},
     *      summary="Вернуть тактику",
     *      description="Вернуть тактику",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function restore(int $id)
    {
        $this->repo->restore($id);

        return new SuccessResource(1);
    }
}
