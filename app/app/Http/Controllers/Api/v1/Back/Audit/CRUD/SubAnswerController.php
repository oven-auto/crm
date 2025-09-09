<?php

namespace App\Http\Controllers\Api\v1\Back\Audit\CRUD;

use App\Http\Controllers\Controller;
use App\Http\Requests\Audit\SubAnswerCreateRequest;
use App\Http\Requests\Audit\SubAnswerListRequest;
use App\Http\Resources\Default\SuccessResource;
use App\Repositories\Audit\SubAnswerRepository;
use Illuminate\Http\Request;

class SubAnswerController extends Controller
{
    public function __construct(
        private SubAnswerRepository $repo,
        public $genus = 'male',
        public $subject = 'Вопрос'
    )
    {
        $this->middleware('notice.message')->only(['store', 'update', 'destroy',]);
    }



    /**
     * @OA\Get(
     *      path="/audits/subanswers",
     *      operationId="getsubanswersQuestionList",
     *      tags={"Аудит стандартов"},
     *      summary="Список ответов на подвопрос стандарта",
     *      description="Список подвопросов стандарта (sub_id = 1,)",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function index(SubAnswerListRequest $request)
    {
        $answers = $this->repo->get($request->validated());

        return response()->json([
            'data' => $answers,
            'success' => 1,
        ]);
    }



    /**
     * @OA\Post(
     *      path="/audits/subanswers",
     *      operationId="postsubanswersQuestionList",
     *      tags={"Аудит стандартов"},
     *      summary="Создать ответ на подвопрос стандарта",
     *      description="Создать  стандарта (sub_id = 1, text = efef)",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function store(SubAnswerCreateRequest $request)
    {
        $answer = $this->repo->create($request->validated());

        return response()->json([
            'data' => $answer,
            'success' => 1,
        ]);
    }



    /**
     * @OA\patch(
     *      path="/audits/subanswers/{subanswerId}",
     *      operationId="patchsubanswersQuestionList",
     *      tags={"Аудит стандартов"},
     *      summary="Изменить ответ на подвопрос стандарта",
     *      description="Изменить  стандарта (sub_id = 1, text = efef)",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function update(int $id, SubAnswerCreateRequest $request)
    {
        $answer = $this->repo->update(id: $id, data:$request->validated());

        return response()->json([
            'data' => $answer,
            'success' => 1,
        ]);
    }



     /**
     * @OA\Get(
     *      path="/audits/subanswers/{subanswerId}",
     *      operationId="showSubAnswer",
     *      tags={"Аудит стандартов"},
     *      summary="Открыть ответ на подвопрос стандарта",
     *      description="Открыть ответ на подвопрос стандарта",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function show(int $id)
    {
        $answer = $this->repo->getById(id: $id);

        return response()->json([
            'data' => $answer,
            'success' => 1,
        ]);
    }



    /**
     * @OA\Delete(
     *      path="/audits/subanswers/{subanswerId}",
     *      operationId="delsubanswersQuestionList",
     *      tags={"Аудит стандартов"},
     *      summary="Удалить ответ на подвопрос стандарта",
     *      description="Удалить  стандарта",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function destroy(int $id)
    {
        $this->repo->delete(id: $id);

        return new SuccessResource(1);
    }



    /**
     * @OA\patch(
     *      path="/audits/subanswers/sort",
     *      operationId="sortSubAnswerAudit",
     *      tags={"Аудит стандартов"},
     *      summary="Помнять сортировку ответов подвопроса аудита",
     *      description="Помнять сортировку ответов подвопроса аудита 
     *      (answers.first = answerId, answers.second = answerId)",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function sort(Request $request)
    {
        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.first' => 'required',
            'answers.second' => 'nullable',
        ]);

        $this->repo->sort($validated);

        return new SuccessResource([]);
    }
}
