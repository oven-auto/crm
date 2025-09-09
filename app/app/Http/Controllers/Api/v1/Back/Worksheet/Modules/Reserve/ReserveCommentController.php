<?php

namespace App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Reserve;

use App\Http\Controllers\Controller;
use App\Http\Requests\Worksheet\Reserve\ReserveCommentCreateRequest;
use App\Http\Requests\Worksheet\Reserve\ReserveCommentRequest;
use App\Http\Resources\Worksheet\Reserve\Comment\CommentCollection;
use App\Repositories\Worksheet\Modules\Reserve\ReserveCommentRepository;

class ReserveCommentController extends Controller
{
    public function __construct(
        private ReserveCommentRepository $repo,
        public $genus = 'male',
        public $subject = 'Комментарий'
    )
    {
        $this->middleware('notice.message')->only(['store', ]);
    }



    public function index(ReserveCommentRequest $request)
    {
        $comments = $this->repo->getWithOutSystem($request->validated());
        
        return new CommentCollection($comments);
    }



    public function store(ReserveCommentCreateRequest $request)
    {
        $this->repo->create($request->validated());

        return response()->json([
            'success' => 1,
        ]);
    }
}
