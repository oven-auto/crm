<?php

namespace App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Credit;

use App\Http\Controllers\Controller;
use App\Http\Requests\Worksheet\Action\CommentListRequest;
use App\Http\Requests\Worksheet\Service\CommentCreateRequest;
use App\Http\Resources\Worksheet\Service\CommentServiceCollection;
use App\Http\Resources\Worksheet\Service\CommentServiceItemResource;
use App\Models\WSMCreditComment;
use Illuminate\Http\Request;

class CreditCommentController extends Controller
{
    public function __construct(
        
    )
    {
        
    }



    /**
     * @OA\Get(
     *      path="/worksheet/modules/credits/comments",
     *      operationId="indexCreditComment",
     *      tags={"МОДУЛЬ РЛ: Кредит"},
     *      summary="Список Комментариев (worksheet_id = int) ",
     *      description="Список Комментариев ",
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function index(CommentListRequest $request)
    {
        $comments = WSMCreditComment::where('worksheet_id', $request->validated())->get();

        return new CommentServiceCollection($comments);
    }



    /**
     * @OA\Post(
     *      path="/worksheet/modules/credits/comments",
     *      operationId="createCreditComment",
     *      tags={"МОДУЛЬ РЛ: Кредит"},
     *      summary="Добавить Комментарий (worksheet_id = int, text = string) ",
     *      description="Добавить Комментарией ",
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function store(CommentCreateRequest $request)
    {
        $comment = WSMCreditComment::create((array) $request->getDTO());

        return new CommentServiceItemResource($comment);
    }
}
