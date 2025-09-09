<?php

namespace App\Http\Controllers\Api\v1\Back\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\Services\ServiceCategoryRequest;
use App\Http\Resources\Default\SuccessResource;
use App\Http\Resources\Services\ServiceCategoryResource;
use App\Repositories\Services\ServiceCategoryRepository;
use Illuminate\Http\Request;

class ServiceCategoryController extends Controller
{
    public function __construct(
        private ServiceCategoryRepository $repo,
        public $subject = 'Категория',
        public $genus = 'female'
    )
    {
        $this->middleware('notice.message')->only(['store', 'update', 'delete', 'restore']);
    }


    
    /**
     * @OA\Get(
     *      path="/finservices/categories",
     *      operationId="getfinservicescategories",
     *      tags={"Финансовые сервисы"},
     *      summary="Категории список",
     *      description="Категории список []",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function index(Request $request)
    {
        $categories = $this->repo->getAll($request->all());

        return ServiceCategoryResource::collection($categories);
    }



        /**
     * @OA\Post(
     *      path="/finservices/categories",
     *      operationId="storefinservicescategories",
     *      tags={"Финансовые сервисы"},
     *      summary="Создать Категории список",
     *      description="Создать Категории список",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function store(ServiceCategoryRequest $request)
    {
        $category = $this->repo->create($request->validated());

        return new ServiceCategoryResource($category);
    }



            /**
     * @OA\Post(
     *      path="/finservices/categories/{id}",
     *      operationId="updatefinservicescategories",
     *      tags={"Финансовые сервисы"},
     *      summary="Изменить Категории список",
     *      description="Изменить Категории список",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function update(int $id, ServiceCategoryRequest $request)
    {
        $category = $this->repo->update($id, $request->validated());

        return new ServiceCategoryResource($category);
    }



     /**
     * @OA\Get(
     *      path="/finservices/categories/{id}",
     *      operationId="showfinservicescategories",
     *      tags={"Финансовые сервисы"},
     *      summary="Открыть Категории список",
     *      description="Открыть Категории список",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function show(int $id)
    {
        $category = $this->repo->getById($id);

        return new ServiceCategoryResource($category);
    }



    /**
     * @OA\Delete(
     *      path="/finservices/categories/{id}",
     *      operationId="sdelfinservicescategories",
     *      tags={"Финансовые сервисы"},
     *      summary="Delete",
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
     *      path="/finservices/categories/{id}/restore",
     *      operationId="restorefinservicescategories",
     *      tags={"Финансовые сервисы"},
     *      summary="Restore",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function restore(int $id)
    {
        $category = $this->repo->restore($id);

        return new ServiceCategoryResource($category);
    }
}
