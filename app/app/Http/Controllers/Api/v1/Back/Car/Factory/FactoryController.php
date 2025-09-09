<?php

namespace App\Http\Controllers\Api\v1\Back\Car\Factory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Car\Factory\FactorySaveRequest;
use App\Http\Resources\Car\Factory\FactorySaveResource;
use App\Http\Resources\Car\Factory\FactoryListCollection;
use App\Models\Factory;
use App\Repositories\Car\Factory\FactoryRepository;
use Illuminate\Http\Request;

class FactoryController extends Controller
{
    public function __construct(
        private FactoryRepository $repo,
        public $genus = 'female',
        public $subject = 'Фабрика',
    )
    {
        $this->middleware('notice.message')->only(['store', 'update', 'delete', 'revert']);
    }


    
    /**
     * @OA\Get(
     *  path="/cars/factories",
     *  operationId="factoriesList",
     *  tags={"Фабрика производства"},
     *  summary="Список фабрик",
     *  description="Список фабрик(?trash)",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function index(Request $request)
    {
        $factories = $this->repo->get($request->all());

        return new FactoryListCollection($factories);
    }



    /**
     * @OA\Post(
     *  path="/cars/factories",
     *  operationId="factoriesStore",
     *  tags={"Фабрика производства"},
     *  summary="Создать фабрик",
     *  description="Создать фабрик(city, country)",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function store(Factory $factory, FactorySaveRequest $request)
    {
        $this->repo->save($factory, $request->validated());

        return (new FactorySaveResource($factory));
    }



    /**
     * @OA\Patch(
     *  path="/cars/factories/{factoryId}",
     *  operationId="factoriesUpdate",
     *  tags={"Фабрика производства"},
     *  summary="Изменить фабрик",
     *  description="Изменить фабрик(city, country)",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function update(Factory $factory, FactorySaveRequest $request)
    {
        $this->repo->save($factory, $request->validated());

        return (new FactorySaveResource($factory));
    }



    /**
     * @OA\Get(
     *  path="/cars/factories/{factoryId}",
     *  operationId="factoriesShow",
     *  tags={"Фабрика производства"},
     *  summary="Открыть фабрик",
     *  description="Открыть фабрик",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function show(Factory $factory)
    {
        return (new FactorySaveResource($factory));
    }



    /**
     * @OA\Delete(
     *  path="/cars/factories/{factoryId}",
     *  operationId="factoriesDelete",
     *  tags={"Фабрика производства"},
     *  summary="Удалить фабрик",
     *  description="Удалить фабрик",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function destroy(Factory $factory)
    {
        $this->repo->delete($factory);

        return response()->json([
            'success' => 1,
        ]);
    }



    /**
     * @OA\Delete(
     *  path="/cars/factories/{factoryId}/restore",
     *  operationId="factoriesRestore",
     *  tags={"Фабрика производства"},
     *  summary="Востановить фабрик",
     *  description="Востановить фабрик",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function revert(Factory $factory)
    {
        $this->repo->restore($factory);

        return response()->json([
            'success' => 1,
        ]);
    }
}
