<?php

namespace App\Http\Controllers\Api\v1\Back\Car\Color;

use App\Http\Controllers\Controller;
use App\Http\Requests\Car\Color\ColorCreateRequest;
use App\Http\Requests\Car\Color\ColorIndexRequest;
use App\Http\Requests\Car\Color\ColorListRequest;
use App\Http\Resources\Car\Color\ColorCollection;
use App\Http\Resources\Car\Color\ColorEditResource;
use App\Http\Resources\Car\Color\ColorListResource;
use App\Models\DealerColor;
use App\Repositories\Car\Color\ColorRepository;

class ColorController extends Controller
{
    public function __construct(
        private ColorRepository $repo,
        public $genus = 'male',
        public $subject = 'Цвет'
    )
    {
        $this->middleware('notice.message')->only(['store', 'update', 'destroy', 'restore']);
    }



    /**
     * @OA\Get(
     *      path="/cars/colors/",
     *      operationId="getDealerColorList",
     *      tags={"CRUD Палитра дилерских цветов"},
     *      summary="Палитра цветов",
     *      description="Палитра цветов (?trash, ?brand_id, ?mark_id, ?name)",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function index(ColorIndexRequest $request) : ColorCollection
    {
        $colors = $this->repo->get($request->all());

        return new ColorCollection($colors);
    }



    /**
     * @OA\Get(
     *      path="/cars/colors/list",
     *      operationId="getColorSelectList",
     *      tags={"CRUD Палитра дилерских цветов"},
     *      summary="Палитра цветов в виде (id, name) для select`ов",
     *      description="Палитра цветов в виде (id, name) для select`ов (mark_id)",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function list(ColorListRequest $request) 
    {
        $list = $this->repo->list($request->mark_id);

        return (ColorListResource::collection($list))->additional(['success' => 1]);
    }



    /**
     * @OA\Post(
     *      path="/cars/colors",
     *      operationId="storeDealerColor",
     *      tags={"CRUD Палитра дилерских цветов"},
     *      summary="Создать цвет",
     *      description="Создать цвет (mark_id, name, base_id, brand_id)",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function store(ColorCreateRequest $request) 
    {
        $dealercolor = $this->repo->store($request->getDTO());

        return (new ColorEditResource($dealercolor));
    }



    /**
     * @OA\Patch(
     *      path="/cars/colors/{dealerColorId}",
     *      operationId="updateDealerColor",
     *      tags={"CRUD Палитра дилерских цветов"},
     *      summary="Изменить цвет",
     *      description="Изменить цвет (mark_id, name, base_id, brand_id)",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function update(DealerColor $dealercolor, ColorCreateRequest $request)
    {
        $this->repo->update($dealercolor, $request->getDTO());

        return (new ColorEditResource($dealercolor));
    }



    /**
     * @OA\Get(
     *      path="/cars/colors/{dealerColorId}",
     *      operationId="showDealerColor",
     *      tags={"CRUD Палитра дилерских цветов"},
     *      summary="Открыть цвет",
     *      description="Открыть цвет (mark_id, name, base_id, brand_id)",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function show(DealerColor $dealercolor) 
    {
        return new ColorEditResource($dealercolor);
    }



    /**
     * @OA\Delete(
     *      path="/cars/colors/{dealerColorId}",
     *      operationId="deleteDealerColor",
     *      tags={"CRUD Палитра дилерских цветов"},
     *      summary="Удалить цвет",
     *      description="Удалить цвет (mark_id, name, base_id, brand_id)",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function delete(DealerColor $dealercolor) 
    {
        $this->repo->delete($dealercolor);

        return response()->json(['success' => 1]);
    }



     /**
     * @OA\Delete(
     *      path="/cars/colors/{dealerColorId}/restore",
     *      operationId="restoreDealerColor",
     *      tags={"CRUD Палитра дилерских цветов"},
     *      summary="Востановиать цвет",
     *      description="Востановиать цвет (mark_id, name, base_id, brand_id)",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function restore(DealerColor $dealercolor) 
    {
        $this->repo->restore($dealercolor);

        return (new ColorEditResource($dealercolor));
    }
}
