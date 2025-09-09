<?php

namespace App\Http\Controllers\Api\v1\Back\Trafic;

use App\Http\Controllers\Controller;
use App\Http\Resources\Trafic\LinkCollection;
use App\Http\Resources\Trafic\LinkResource;
use App\Models\Trafic;
use App\Models\TraficLink;
use App\Repositories\Trafic\TraficLinkRepository;
use Illuminate\Http\Request;

class TraficLinkController extends Controller
{
    public $repo;

    public function __construct(TraficLinkRepository $repo)
    {
        $this->repo = $repo;
    }



    /**
     * @OA\Get(
     *      path="/trafic/links",
     *      operationId="getTraficLinksList",
     *      tags={"Трафик"},
     *      summary="Получить список ссылок добавленых в трафик",
     *      description="Получить список ссылок добавленых в трафик",
     *      @OA\RequestBody(
     *          required=true,
     *          description="TraficId",
     *          @OA\JsonContent(
     *              required={"trafic_id"},
     *              @OA\Property(property="trafic_id", type="string", format="email", example="1")
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'trafic_id' => 'required|numeric',
        ]);

        $links = $this->repo->get($validated['trafic_id']);

        return new LinkCollection($links);
    }



    /**
     * @OA\Post(
     *      path="/trafic/links/{traficId}",
     *      operationId="storeTraficLinks",
     *      tags={"Трафик"},
     *      summary="Добавить ссылку в трафик",
     *      description="Добавить ссылку в трафик",
     *      @OA\RequestBody(
     *          required=true,
     *          description="TraficId",
     *          @OA\JsonContent(
     *              required={"url"},
     *              @OA\Property(property="url", type="string", format="string", example="http://example.com")
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="traficId",
     *          description="Идентификатор трафика",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function store(Trafic $trafic, Request $request)
    {
        $validated = $request->validate([
            'url' => 'required',
        ]);

        return response()->json([
            'data' => new LinkResource($this->repo->createTraficLink($trafic, $validated)),
            'success' => 1,
            'message' => 'Ссылка добавлена.'
        ]);
    }



    public function update(TraficLink $link, Request $request)
    {
        return response()->json([
            'success' => 1,
            'message' => 'Изменение ссылки трафика более не работает.'
        ]);
    }



    /**
     * @OA\Delete(
     *      path="/trafic/links/{traficLink}",
     *      operationId="deleteTraficLink",
     *      tags={"Трафик"},
     *      summary="Удалить ссылку в трафике",
     *      description="Удалить ссылку в трафик",
     *      @OA\Parameter(
     *          name="traficLink",
     *          description="Идентификатор ссылки",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function delete(TraficLink $link)
    {
        $this->repo->delete($link);

        return response()->json([
            'success' => 1,
            'message' => 'Ссылка удалена.'
        ]);
    }



    /**
     * @OA\Get(
     *      path="/trafic/links/count",
     *      operationId="getTraficLinksListCount",
     *      tags={"Трафик"},
     *      summary="Получить кол-во ссылок добавленых в трафик",
     *      description="Получить кол-во ссылок добавленых в трафик",
     *      @OA\RequestBody(
     *          required=true,
     *          description="TraficId",
     *          @OA\JsonContent(
     *              required={"trafic_id"},
     *              @OA\Property(property="trafic_id", type="string", format="email", example="1")
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function count(Request $request)
    {
        $validated = $request->validate([
            'trafic_id' => 'required',
        ]);

        return response()->json([
            'count' => $this->repo->count($validated['trafic_id']),
            'success' => 1,
        ]);
    }
}
