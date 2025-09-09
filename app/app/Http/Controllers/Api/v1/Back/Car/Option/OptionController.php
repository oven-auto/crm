<?php

namespace App\Http\Controllers\Api\v1\Back\Car\Option;

use App\Http\Controllers\Controller;
use App\Http\Requests\Car\Option\OptionCreateRequest;
use App\Http\Requests\Car\Option\OptionIndexRequest;
use App\Http\Resources\Car\Option\OptionIndexResource;
use App\Http\Resources\Car\Option\OptionItemResource;
use App\Models\Car;
use App\Repositories\Car\Option\OptionRepository;
use App\Models\Option;
use App\Services\Car\Option\List\OptionListService;

class OptionController extends Controller
{
    public function __construct(
        private OptionRepository $repo,
        public $genus = 'female',
        public $subject = 'Опция'
    )
    {
        $this->middleware('notice.message')->only(['store', 'update', 'delete', 'restore']);
    }



    /**
     * @OA\Get(
     *  path="/cars/options",
     *  operationId="optionsList",
     *  tags={"Опции автомобиля"},
     *  summary="Список опций",
     *  description="Список опций(?trash, ?car_id, ?name, ?code, ?mark_id)",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function index(OptionIndexRequest $request, OptionListService $service)
    {
        $car = isset($data['car_id']) ? Car::findOrFail($data['car_id']) : null;

        $res = $service->getList($request->all());

        return new OptionIndexResource(['res' => $res, 'car' => $car]);
    }



    /**
     * @OA\Post(
     *  path="/cars/options",
     *  operationId="optionsStore",
     *  tags={"Опции автомобиля"},
     *  summary="Создать опций",
     *  description="Создать опций(brand_id, name, code, mark_id)",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function store(OptionCreateRequest $request)
    {
        $option = $this->repo->store($request->all());

        return (new OptionItemResource($option));
    }



    /**
     * @OA\Patch(
     *  path="/cars/options/{optionId}",
     *  operationId="optionsUpdate",
     *  tags={"Опции автомобиля"},
     *  summary="Изменит опций",
     *  description="Изменит опций(brand_id, name, code, mark_id)",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function update(Option $option, OptionCreateRequest $request)
    {
        $this->repo->update($option, $request->all());

        return (new OptionItemResource($option));
    }



    /**
     * @OA\Get(
     *  path="/cars/options/{optionId}",
     *  operationId="optionsShow",
     *  tags={"Опции автомобиля"},
     *  summary="Открыт опций",
     *  description="Открыт опций",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function show(Option $option)
    {
        return (new OptionItemResource($option));
    }



    /**
     * @OA\Delete(
     *  path="/cars/options/{optionId}",
     *  operationId="optionsDelete",
     *  tags={"Опции автомобиля"},
     *  summary="Удалитб опций",
     *  description="Удалитб опций",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function delete(Option $option)
    {
        $this->repo->delete($option);

        return response()->json(['success' => 1]);
    }



    /**
     * @OA\Patch(
     *  path="/cars/options/{optionId}/restore",
     *  operationId="optionsRestore",
     *  tags={"Опции автомобиля"},
     *  summary="Востановить опций",
     *  description="Востановить опций",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  ),
     * )
     */
    public function restore(Option $option)
    {
        $this->repo->restore($option);

        return (new OptionItemResource($option));
    }
}
