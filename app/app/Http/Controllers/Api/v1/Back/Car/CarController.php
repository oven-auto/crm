<?php

namespace App\Http\Controllers\Api\v1\Back\Car;

use App\Classes\Notice\Notice;
use App\Http\Controllers\Controller;
use App\Http\Requests\Car\OverPrice\OverPriceRequest;
use App\Http\Requests\Car\CarCreateRequest;
use App\Http\Resources\Car\Car\CarItemResource;
use App\Http\Resources\Car\Car\CarListCollection;
use App\Http\Resources\Default\SuccessResource;
use Illuminate\Http\Request;
use App\Models\Car;
use App\Repositories\Car\Car\CarRepository;
use App\Repositories\Car\Car\DTO\LogisticDateDTO;
use Exception;

class CarController extends Controller
{
    public function __construct(
        private CarRepository $repo,
        public $subject = 'Автомобиль',
        public $genus = 'male')
    {
        $this->middleware('carfilter')->only('index');
        $this->middleware('notice.message')->only(['store', 'update',]);
    }



    /**
     * @OA\Get(
     *      path="/cars",
     *      operationId="carsList",
     *      tags={"Новый автомобиль"},
     *      summary="Список новых автомобилей",
     *      description="Список новых автомобилейв",
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/CarFilter",
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function index(Request $request)
    {
        $cars = $this->repo->paginate($request->all());
        
        return new CarListCollection($cars);
    }



    /**
     * @OA\Post(
     *      path="/cars",
     *      operationId="storeCar",
     *      tags={"Новый автомобиль"},
     *      summary="Создать новый автомобиль",
     *      description="Создать новый автомобиль",
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/CarCreateRequest",
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      )
     * )
     */
    public function store(CarCreateRequest $request)
    {
        $car = $this->repo->store($request->validated());

        return (new CarItemResource($car));
    }



    /**
     * @OA\Patch(
     *      path="/cars/{id}",
     *      operationId="updateCar",
     *      tags={"Новый автомобиль"},
     *      summary="Изменить новый автомобиль",
     *      description="Изменить новый автомобиль",
     *      @OA\Parameter(
     *          name="id",
     *          description="Идентификатор машины",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/CarCreateRequest",
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      )
     * )
     */
    public function update(Car $car, CarCreateRequest $request)
    {
        $this->repo->update($car, $request->validated());

        return (new CarItemResource($car));
    }



    /**
     * @OA\Get(
     *      path="/cars/{id}",
     *      operationId="getCar",
     *      tags={"Новый автомобиль"},
     *      summary="Открыть карточку нового автомобиля",
     *      description="Открыть карточку нового автомобиля",
     *      @OA\Parameter(
     *          name="id",
     *          description="Идентификатор автомобиля",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *      ),
     * )
     */
    public function show(Car $car)
    {
        return new CarItemResource($car);
    }



        /**
     * @OA\Post(
     *      path="/cars/overprice/{id}",
     *      operationId="storeCarOverPrice",
     *      tags={"Новый автомобиль"},
     *      summary="Добавить/Изменить стоимость переоценки автомобиля (воздух)",
     *      description="Добавить/Изменить стоимость переоценки автомобиля (воздух)",
     *      @OA\RequestBody(
     *          required=true,
     *          description="price",
     *          @OA\JsonContent(
     *              required={"price"},
     *              @OA\Property(property="price", type="integer", format="integer", example="10000")
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          description="Идентификатор автомобиля",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *      ),
     * )
     */
    public function makeOverPrice(Car $car, OverPriceRequest $request)
    {
        $this->repo->saveOverPrice($car, $request->price);

        return (new CarItemResource($car))
            ->additional(['message' => 'Дооценка зарегестрирована']);
    }



    /**
     * @OA\Get(
     *      path="/cars/overprice/{id}",
     *      operationId="getCarOverPrice",
     *      tags={"Новый автомобиль"},
     *      summary="Получить стоимость переоценки автомобиля (воздух)",
     *      description="Получить стоимость переоценки автомобиля (воздух)",
     *      @OA\Parameter(
     *          name="id",
     *          description="Идентификатор автомобиля",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *      ),
     * )
     */
    public function getOverPrice(Car $car)
    {
        return response()->json([
            'data' => [
                'price' => $car->over_price->id ? $car->over_price->price : '',
                'author' => $car->over_price->id ? $car->over_price->author->cut_name : '',
                'date' => $car->over_price->id ? $car->over_price->updated_at->format('d.m.Y (H:i)') : '',
            ],
            'success' => 1,
        ]);
    }



    /**
     * @OA\Get(
     *      path="/cars/{id}/history",
     *      operationId="getCarHistory",
     *      tags={"Новый автомобиль"},
     *      summary="Получить историю автомобиля",
     *      description="Получить историю автомобиля",
     *      @OA\Parameter(
     *          name="id",
     *          description="Идентификатор автомобиля",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *      ),
     * )
     */
    public function history(Car $car)
    {
        return response()->json([
            'data' => $car->history->map(function($item){
                return [
                    'author' => $item->author->cut_name,
                    'created_at' => $item->created_at->format('d.m.Y (H:i)'),
                    'comment' => $item->comment,
                    'type' => $item->type,
                ];
            }),
            'success' => 1,
        ]);
    }



        /**
     * @OA\Get(
     *      path="/cars/{id}/tuning",
     *      operationId="getCarTuning",
     *      tags={"Новый автомобиль"},
     *      summary="Получить установленный тюнинг на автомобиле",
     *      description="Получить установленный тюнинг на автомобиле",
     *      @OA\Parameter(
     *          name="id",
     *          description="Идентификатор автомобиля",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *      ),
     * )
     */
    public function tuning(Car $car)
    {
        return response()->json([
            'data' => $car->tuning->map(function($item){
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'trash' => $item->deleted_at ? 1 : 0,
                ];
            }),
            'success' => 1,
        ]);
    }



        /**
     * @OA\Delete(
     *      path="/cars/{id}",
     *      operationId="deleteCar",
     *      tags={"Новый автомобиль"},
     *      summary="Удалить автомобиль",
     *      description="Удалить автомобиль",
     *      @OA\Parameter(
     *          name="id",
     *          description="Идентификатор автомобиля",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *      ),
     * )
     */
    public function destroy(int $id)
    {
        $car = Car::findOrFail($id);

        if($car->isReserved())
            throw new Exception('Имеется резерв. Операция не возможна.');

        if(!$car->isApplication())
            throw new Exception('Автомобиль не является заявкой.  Операция не возможна.');
        
        $data['deleted_date'] = now()->format('d.m.Y');

        $car->saveLogisticDates(new LogisticDateDTO($data ?? []));

        $car->load('logistic_dates');

        $this->repo->setCarStatus($car);
        
        $car->delete();

        return new SuccessResource(1);
    }



    /**
     * @OA\Patch(
     *      path="cars/restore/{id}",
     *      operationId="restoreCar",
     *      tags={"Востановить автомобиль"},
     *      summary="Востановить автомобиль",
     *      description="Востановить автомобиль",
     *      @OA\Parameter(
     *          name="id",
     *          description="Идентификатор автомобиля",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *      ),
     * )
     */
    public function restore(int $id)
    {
        $car = car::onlyTrashed()->findOrFail($id);

        $car->restore();

        $data['application_date'] = $car->created_at->format('d.m.Y');

        $car->saveLogisticDates(new LogisticDateDTO($data));

        $car->load('logistic_dates');

        $this->repo->setCarStatus($car);

        return response()->json([
            'message' => 'Пометка об удалении снята.',
            'success' => 1,
        ]);
    }
}
