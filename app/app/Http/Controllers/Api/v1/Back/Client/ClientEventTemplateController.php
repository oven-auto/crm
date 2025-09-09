<?php

namespace App\Http\Controllers\Api\v1\Back\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\ClientEventTemplateCreateRequest;
use App\Http\Resources\Client\ClientEvent\ClientEventTemplateCollection;
use App\Http\Resources\Client\ClientEvent\ClientEventTemplateItemResource;
use App\Repositories\Client\ClientEventTemplateRepository;
use Illuminate\Http\Request;

class ClientEventTemplateController extends Controller
{
    public function __construct(
        private ClientEventTemplateRepository $repo,
        public $subject = 'Шаблон',
        public $genus = 'male',
    )
    {
        $this->middleware('notice.message')->only(['store', 'update', 'delete', 'restore']);
    }



    /**
     * @OA\Get(
     *      path="/client/event/templates",
     *      operationId="getTemplates",
     *      tags={"Шаблоны"},
     *      summary="Список шаблонов {trash?}",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function index(Request $request)
    {
        $templates = $this->repo->get($request->all());

        return new ClientEventTemplateCollection($templates);
    }



    /**
     * @OA\Post(
     *      path="/client/event/templates",
     *      operationId="storeTemplates",
     *      tags={"Шаблоны"},
     *      summary="Создать шаблонов",
     *      description="Создать шаблонов",
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/ClientEventTemplateCreateRequest",
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function store(ClientEventTemplateCreateRequest $request)
    {   
        $template = $this->repo->create($request->getDTO());

        return new ClientEventTemplateItemResource($template);
    }



    /**
     * @OA\Patch(
     *      path="/client/event/templates/{id}",
     *      operationId="updateTemplates",
     *      tags={"Шаблоны"},
     *      summary="Изменить шаблонов",
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/ClientEventTemplateCreateRequest",
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function update(int $id, ClientEventTemplateCreateRequest $request)
    {
        $template = $this->repo->update($id, $request->getDTO());

        return new ClientEventTemplateItemResource($template);
    }



    /**
     * @OA\Get(
     *      path="/client/event/templates/{id}",
     *      operationId="showTemplates",
     *      tags={"Шаблоны"},
     *      summary="Открыть шаблонов",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function show(int $id)
    {
        $template = $this->repo->getById($id);

        return new ClientEventTemplateItemResource($template);
    }



    /**
     * @OA\Delete(
     *      path="/client/event/templates/{id}",
     *      operationId="deleteTemplates",
     *      tags={"Шаблоны"},
     *      summary="Удалить шаблонов",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function destroy(int $id)
    {
        $template = $this->repo->delete($id);

        return new ClientEventTemplateItemResource($template);
    }



    /**
     * @OA\Patch(
     *      path="/client/event/templates/{id}/restore",
     *      operationId="restoreTemplates",
     *      tags={"Шаблоны"},
     *      summary="Востановить шаблонов",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function restore(int $id)
    {
        $template = $this->repo->restore($id);

        return new ClientEventTemplateItemResource($template);
    }
}
