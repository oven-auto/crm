<?php

namespace App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\Worksheet\Service\CommentListRequest;
use App\Http\Requests\Worksheet\Service\CommentCreateRequest;
use App\Http\Resources\Worksheet\Service\CommentServiceCollection;
use App\Http\Resources\Worksheet\Service\CommentServiceItemResource;
use App\Models\WSMServiceComment;

class ServiceCommentController extends Controller
{
    public function __construct(
        
    )
    {
        
    }



    /**
     * @OA\Get(
     *      path="/worksheet/modules/services/comments",
     *      operationId="indexServiceComment",
     *      tags={"МОДУЛЬ РЛ: Финансовые сервисы"},
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
        $comments = WSMServiceComment::where('worksheet_id', $request->validated())->get();

        return new CommentServiceCollection($comments);
    }



    /**
     * @OA\Post(
     *      path="/worksheet/modules/services/comments",
     *      operationId="createServiceComment",
     *      tags={"МОДУЛЬ РЛ: Финансовые сервисы"},
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
        $comment = WSMServiceComment::create((array) $request->getDTO());

        return new CommentServiceItemResource($comment);
    }
}
