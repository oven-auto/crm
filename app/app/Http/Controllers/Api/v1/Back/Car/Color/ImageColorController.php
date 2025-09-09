<?php

namespace App\Http\Controllers\Api\v1\Back\Car\Color;

use App\Http\Controllers\Controller;
use App\Http\Requests\Car\Color\ColorImageRequest;
use App\Http\Resources\Car\Color\ImageResource;
use App\Models\DealerColorImage;
use App\Repositories\Car\Color\ColorRepository;
use Illuminate\Http\Request;

class ImageColorController extends Controller
{
    public function __construct(
        private ColorRepository $repo,
        public $genus = 'female',
        public $subject = 'Картинка цвета'
    )
    {
        $this->middleware('notice.message')->only(['store', 'update', 'destroy']);
    }



    /**
     * @OA\Get(
     *      path="/cars/colors/images",
     *      operationId="getColorList",
     *      tags={"CRUD Палитра дилерских цветов", "Изображение цвета"},
     *      summary="Список картинок цвета",
     *      description="Список картинок цвета",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="color_id",
     *                  description="ID дилерского цвета",
     *                  type="integer",
     *                  format="integer"
     *              )
     *         )
     *      )
     *     )
     */
    public function index(Request $request)
    {   
        $validated = $request->validate([
            'color_id' => 'sometimes|numeric'
        ]);

        $images = $this->repo->getColorImages($validated);
        
        return response()->json([
            'data' => ImageResource::collection($images),
            'success' => 1,
        ]);
    }



    /**
     * @OA\Post(
     *      path="/cars/colors/images",
     *      operationId="storeColorList",
     *      tags={"CRUD Палитра дилерских цветов",  "Изображение цвета"},
     *      summary="Добавить",
     *      description="Добавить",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="color_id",
     *                  description="ID дилерского цвета",
     *                  type="integer",
     *                  format="integer"
     *              ),
     *              @OA\Property(
     *                  property="bodywork",
     *                  description="ID кузова",
     *                  type="integer",
     *                  format="integer"
     *              ),
     *              @OA\Property(
     *                  property="image",
     *                  description="Картинка",
     *                  type="file",
     *                  format="file"
     *              )
     *         )
     *      )
     *     )
     */
    public function store(ColorImageRequest $request)
    {
        $image = $this->repo->appendImage($request->validated());

        return response()->json([
            'data' => new ImageResource($image),
            'success' => 1,
        ]);
    }



    /**
     * @OA\Patch(
     *      path="/cars/colors/images/{imageId}",
     *      operationId="updateColorList",
     *      tags={"CRUD Палитра дилерских цветов",  "Изображение цвета"},
     *      summary="Изменить",
     *      description="Изменить",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="color_id",
     *                  description="ID дилерского цвета",
     *                  type="integer",
     *                  format="integer"
     *              ),
     *              @OA\Property(
     *                  property="bodywork",
     *                  description="ID кузова",
     *                  type="integer",
     *                  format="integer"
     *              ),
     *              @OA\Property(
     *                  property="image",
     *                  description="Картинка",
     *                  type="file",
     *                  format="file"
     *              )
     *         )
     *      )
     *     )
     */
    public function update(DealerColorImage $image, ColorImageRequest $request)
    {
        $image = $this->repo->updateImage($image, $request->validated());

        return response()->json([
            'data' => new ImageResource($image),
            'success' => 1,
        ]);
    }



    /**
     * @OA\Delete(
     *      path="/cars/colors/images/{imageId}",
     *      operationId="deleteColorList",
     *      tags={"CRUD Палитра дилерских цветов",  "Изображение цвета"},
     *      summary="Удалить",
     *      description="Удалить",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function delete(DealerColorImage $image)
    {
        $this->repo->deleteImage($image);

        return response()->json([
            'success' => 1,
        ]);
    }
}
