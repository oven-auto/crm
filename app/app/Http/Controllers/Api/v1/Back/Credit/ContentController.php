<?php

namespace App\Http\Controllers\Api\v1\Back\Credit;

use App\Http\Controllers\Controller;
use App\Http\Requests\Credit\Content\CreditContentCreateRequest;
use App\Http\Requests\Credit\Content\CreditContentListRequest;
use App\Http\Resources\Credit\Content\CreditContentCollection;
use App\Http\Resources\Credit\Content\CreditContentItemResource;
use App\Http\Resources\Default\SuccessResource;
use App\Repositories\Credit\Content\CreditContentRepository;

class ContentController extends Controller
{
    public function __construct(
        private CreditContentRepository $repo,
        public $genus = 'male',
        public $subject = 'Контент'
    )
    {
        $this->middleware('notice.message')->only(['store', 'update', 'destroy', 'restore']);
    }



    /**
     * @OA\Get(
     *      path="/creditlist/content",
     *      operationId="getTacticContent",
     *      tags={"Контент кредита"},
     *      summary="Контент кредита",
     *      description="Контент кредита (trash?)",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function index(CreditContentListRequest $request)
    {
        $contents = $this->repo->get($request->all());

        return new CreditContentCollection($contents);
    }



    /**
     * @OA\Post(
     *      path="/creditlist/content",
     *      operationId="postTacticContent",
     *      tags={"Контент кредита"},
     *      summary="Создать Контент кредита",
     *      description="Создать Контент кредита (name)",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function store(CreditContentCreateRequest $request)
    {
        $content = $this->repo->create($request->getDTO());

        return new CreditContentItemResource($content);
    }



    /**
     * @OA\Patch(
     *      path="/creditlist/content/{id}",
     *      operationId="patchTacticContent",
     *      tags={"Контент кредита"},
     *      summary="Изменить Контент кредита",
     *      description="Изменить Контент кредита (name)",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function update(int $id, CreditContentCreateRequest $request)
    {
        $content = $this->repo->update($id, $request->getDTO());

        return new CreditContentItemResource($content);
    }



    /**
     * @OA\Get(
     *      path="/creditlist/content/{id}",
     *      operationId="showTacticContent",
     *      tags={"Контент кредита"},
     *      summary="Открыть Контент кредита",
     *      description="Открыть Контент кредита",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function show(int $id)
    {
        $content = $this->repo->getById($id);

        return new CreditContentItemResource($content);
    }



    /**
     * @OA\Delete(
     *      path="/creditlist/content/{id}",
     *      operationId="delTacticContent",
     *      tags={"Контент кредита"},
     *      summary="Удалить Контент кредита",
     *      description="Удалить Контент кредита",
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
     *      path="/creditlist/content/{id}restore",
     *      operationId="restTacticContent",
     *      tags={"Контент кредита"},
     *      summary="Востановить Контент кредита",
     *      description="Востановить Контент кредита",
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
