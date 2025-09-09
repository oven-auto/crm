<?php

namespace App\Http\Controllers\Api\v1\Back\Audit\CRUD;

use App\Http\Controllers\Controller;
use App\Http\Requests\Audit\QuestionListRequest;
use App\Http\Requests\Audit\QuestionRequest;
use App\Http\Resources\Audit\QuestionCollection;
use App\Http\Resources\Audit\QuestionEditResource;
use App\Http\Resources\Default\SuccessResource;
use App\Repositories\Audit\QuestionRepository;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function __construct(
        private QuestionRepository $repo,
        public $subject = 'Стандарт аудита',
        public $genus = 'male'
    )
    {
		$this->middleware('notice.message')->only(['store', 'update', 'destroy', 'restore']);
    }



    /**
     * @OA\Get(
     *      path="/audits/questions",
     *      operationId="getAuditQuestionList",
     *      tags={"Аудит стандартов"},
     *      summary="Список ответов аудита",
     *      description="Список ответов аудита (audit_id = 1, ?trash = 1)",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function index(QuestionListRequest $request)
    {
        $questions = $this->repo->get($request->validated());

        return new QuestionCollection($questions);
    }



    /**
     * @OA\Post(
     *      path="/audits/questions",
     *      operationId="storeAuditQuestion",
     *      tags={"Аудит стандартов"},
     *      summary="Создать вопрос аудит",
     *      description="Создать вопрос аудит (audit_id, name, text, weight, is_stoped, answers[positive = bool, negative = bool, neutral = bool])",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function store(QuestionRequest $request)
    {
        $question = $this->repo->create($request->validated());

        return new QuestionEditResource($question);
    }



    /**
     * @OA\Patch(
     *      path="/audits/questions/{questionId}",
     *      operationId="updateAuditQuestion",
     *      tags={"Аудит стандартов"},
     *      summary="Изменить вопрос аудит",
     *      description="Изменить вопрос аудит (audit_id, name, text, weight, is_stoped, answers[positive = bool, negative = bool, neutral = bool])",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function update(int $id, QuestionRequest $request)
    {
        $question = $this->repo->update($id, $request->validated());

        return new QuestionEditResource($question);
    }



    /**
     * @OA\Get(
     *      path="/audits/questions/{questionId}",
     *      operationId="getAuditQuestion",
     *      tags={"Аудит стандартов"},
     *      summary="Открыть ответов аудита",
     *      description="Открыть ответ аудита",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function show(int $id)
    {
        $question = $this->repo->getById($id);

        return new QuestionEditResource($question);
    }



    /**
     * @OA\Delete(
     *      path="/audits/questions/{questionId}",
     *      operationId="deleteAuditQuestion",
     *      tags={"Аудит стандартов"},
     *      summary="Удалить ответов аудита",
     *      description="Удалить ответ аудита",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function destroy(int $id)
    {
        $question = $this->repo->delete($id);

        return response()->json([
            'success' => 1,
        ]);
    }



    /**
     * @OA\put(
     *      path="/audits/questions/{questionId}/restore",
     *      operationId="restoreAuditQuestion",
     *      tags={"Аудит стандартов"},
     *      summary="Востановить ответов аудита",
     *      description="Востановить ответ аудита",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function restore(int $id)
    {
        $question = $this->repo->restore($id);

        return response()->json([
            'success' => 1,
        ]);
    }



    /**
     * @OA\patch(
     *      path="/audits/questions/sort",
     *      operationId="sortAuditQuestion",
     *      tags={"Аудит стандартов"},
     *      summary="Помнять сортировку ответов аудита",
     *      description="Помнять сортировку ответ аудита (questions.first = questionIdfirst, questions.second = questionIdSecond)",
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

        return new SuccessResource(1);
    }
}
