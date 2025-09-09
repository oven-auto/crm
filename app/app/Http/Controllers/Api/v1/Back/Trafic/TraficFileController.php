<?php

namespace App\Http\Controllers\Api\v1\Back\Trafic;

use App\Http\Controllers\Controller;
use App\Http\Resources\Trafic\TraficFile\TraficFileResource;
use App\Models\TraficFile;
use Illuminate\Http\Request;
use App\Models\Trafic;

class TraficFileController extends Controller
{
    private $repo;

    public function __construct(\App\Repositories\Trafic\TraficFileRepository $repo)
    {
        $this->repo = $repo;
    }



        /**
     * @OA\Get(
     *      path="/trafic/files/{traficId}}",
     *      operationId="getTraficFilesList",
     *      tags={"Трафик"},
     *      summary="Получить список файлов добавленых в трафик",
     *      description="Получить список файлов добавленых в трафик",
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
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function index(Trafic $trafic)
    {
        $trafics = $this->repo->get($trafic);

        return response()->json([
            'data' => TraficFileResource::collection($trafics),
            'success' => 1,
        ]);
    }



    /**
     * @OA\Post(
     *      path="/trafic/files/{traficId}",
     *      operationId="storeTraficFiles",
     *      tags={"Трафик"},
     *      summary="Добавить файлы в трафик",
     *      description="Добавить файлы в трафик",
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
        $files = $this->repo->saveTraficFiles($trafic, $request->allFiles());

        return response()->json([
            'data' => TraficFileResource::collection($files),
            'success' => 1,
            'message' => 'Файлы добавлены.',
        ]);
    }


    
    /**
     * @OA\Delete(
     *      path="/trafic/files/{fileId}",
     *      operationId="deleteTraficFiles",
     *      tags={"Трафик"},
     *      summary="Удалить файл в трафике",
     *      description="Удалить файл в трафик",
     *      @OA\Parameter(
     *          name="fileId",
     *          description="Идентификатор файла",
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
    public function destroy(TraficFile $file)
    {
        $this->repo->delete($file);

        return response()->json([
            'data' => [],
            'message' => 'Файл удален.',
            'success' => 1,
        ]);
    }
}
