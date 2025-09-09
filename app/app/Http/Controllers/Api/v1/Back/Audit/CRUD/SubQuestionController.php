<?php

namespace App\Http\Controllers\Api\v1\Back\Audit\CRUD;

use App\Http\Controllers\Controller;
use App\Http\Requests\Audit\SubQuestionListRequest;
use App\Http\Requests\Audit\SubQuestionStoreRequest;
use App\Http\Resources\Audit\SubQuestionCollection;
use App\Http\Resources\Audit\SubQuestionEditResource;
use App\Http\Resources\Default\SuccessResource;
use App\Repositories\Audit\SubQuestionRepository;
use Illuminate\Http\Request;

class SubQuestionController extends Controller
{
    public function __construct(
        private SubQuestionRepository $repo,
        public $genus = 'male',
        public $subject = 'Вопрос'
    )
    {
        $this->middleware('notice.message')->only(['store', 'update', 'destroy',]);
    }



    /**
     * @OA\Get(
     *      path="/audits/subquestions",
     *      operationId="getSubAuditQuestionList",
     *      tags={"Аудит стандартов"},
     *      summary="Список подвопросов стандарта",
     *      description="Список подвопросов стандарта (question_id = 1,)",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function index(SubQuestionListRequest $request)
    {
        $subs = $this->repo->get(data: $request->validated());   

        return new SubQuestionCollection($subs);
    }



    /**
     * @OA\Post(
     *      path="/audits/subquestions",
     *      operationId="postSubAuditQuestionList",
     *      tags={"Аудит стандартов"},
     *      summary="Создать подвопрос стандарта",
     *      description="Создать подвопрос стандарта (question_id = 1, text = укцк, multiple = bool)",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function store(SubQuestionStoreRequest $request)
    {       
        $sub = $this->repo->create(data: $request->validated()); 

        return new SubQuestionEditResource($sub);
    }



    /**
     * @OA\Patch(
     *      path="/audits/subquestions/{subQuestionId}",
     *      operationId="updateSubAuditQuestionList",
     *      tags={"Аудит стандартов"},
     *      summary="Изменить подвопрос стандарта",
     *      description="Изменить подвопрос стандарта (question_id = 1, text = укцк, multiple = bool)",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function update(int $id, SubQuestionStoreRequest $request)
    {
        $sub = $this->repo->update(id: $id, data: $request->validated()); 

        return new SubQuestionEditResource($sub);
    }



    /**
     * @OA\Get(
     *      path="/audits/subquestions/{subQuestionId}",
     *      operationId="showSubAuditQuestionList",
     *      tags={"Аудит стандартов"},
     *      summary="Открыть подвопрос стандарта",
     *      description="Открыть подвопрос стандарта ",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function show(int $id)
    {
        $sub = $this->repo->getById(id: $id);

        return new SubQuestionEditResource($sub);
    }



    /**
     * @OA\Delete(
     *      path="/audits/subquestions/{subQuestionId}",
     *      operationId="DelSubAuditQuestionList",
     *      tags={"Аудит стандартов"},
     *      summary="Удалить подвопрос стандарта",
     *      description="Удалить подвопрос стандарта ",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function destroy(int $id)
    {
        $status = $this->repo->delete(id: $id);

        return new SuccessResource($status);
    }



    /**
     * @OA\patch(
     *      path="/audits/subquestions/sort",
     *      operationId="sortSubAuditQuestion",
     *      tags={"Аудит стандартов"},
     *      summary="Помнять сортировку подвопросов аудита",
     *      description="Помнять сортировку подвопросов аудита (questions.first = questionIdfirst, questions.second = questionIdSecond)",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function sort(Request $request)
    {
        $validated = $request->validate([
            'questions' => 'required|array',
            'questions.first' => 'required',
            'questions.second' => 'nullable',
        ]);

        $this->repo->sort($validated);

        return new SuccessResource(0);
    }
}
