<?php

namespace App\Http\Controllers\Api\v1\Back\Worksheet\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\Worksheet\Modules\Redemption\RedemptionStoreRequest;
use App\Http\Resources\Worksheet\Link\LinkResource;
use App\Http\Resources\Worksheet\Modules\RedemptionCollection;
use App\Http\Resources\Worksheet\Modules\RedemptionListResource;
use App\Http\Resources\Worksheet\Modules\RedemptionResource;
use App\Models\Worksheet;
use App\Models\WSMRedemptionCar;
use App\Repositories\Worksheet\Modules\Redemption\RedemptionRepository;
use Illuminate\Http\Request;


class RedemptionController extends Controller
{
    protected $repo;

    public function __construct(RedemptionRepository $repo)
    {
        $this->repo = $repo;
    }



    /**
     * ПОЛУЧИТЬ СПИСОК ОЦЕНОК
     * @param int $worksheet
     * @param Request $request
     * @return mixed \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function list(Request $request ) 
    {
        $redemptions = $this->repo->paginate($request->all());
        
        if($redemptions)
            return (RedemptionListResource::collection($redemptions))->additional(['success' => 1]);

        return response()->json([
            'data' => [],
            'success' => 1
        ]);
    }



    /**
     * ПОЛУЧИТЬ СПИСОК ОЦЕНОК В РАБОЧЕМ ЛИСТЕ
     * @param Request $request
     * @return mixed RedemptionCollection
     */
    public function index(int $worksheet) : RedemptionCollection
    {
        $redemptions = $this->repo->get($worksheet);
        return new RedemptionCollection($redemptions);
    }



    /**
     * СОЗДАТЬ ОЦЕНКУ
     * @param Worksheet $worksheet
     * @param RedemptionStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Worksheet $worksheet, RedemptionStoreRequest $request) : \Illuminate\Http\JsonResponse
    {
        $redemption = $this->repo->store($worksheet, $request->validated());

        return response()->json([
            'data' => new RedemptionResource($redemption),
            'success' => 1,
            'message' => 'Оценка создана'
        ]);
    }



    /**
     * СОХРАНИТЬ СВОДНЫЕ ДАННЫЕ ОЦЕНКИ (РАСЧЕТНАЯ ЦЕНА, ПРЕДЛОЖЕНИЕ, ФАКТ)
     * @param WSMRedemptionCar $redemption
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveprice(WSMRedemptionCar $redemption, Request $request) : \Illuminate\Http\JsonResponse
    {
        $this->repo->save($redemption, $request->all());

        $redemption->fresh();

        return response()->json([
            'data' => new RedemptionResource($redemption),
            'success' => 1,
            'message' => 'Оценка изменена'
        ]);
    }



    /**
     * ИЗМЕНИТЬ ОЦЕНКУ
     * @param Worksheet $worksheet
     * @param RedemptionStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(WSMRedemptionCar $redemption, RedemptionStoreRequest $request) : \Illuminate\Http\JsonResponse
    {
        $this->repo->update($redemption, $request->validated());

        return response()->json([
            'data' => new RedemptionResource($redemption),
            'success' => 1,
            'message' => 'Оценка изменена'
        ]);
    }



    /**
     * СОХРАНИТЬ ССЫЛКУ
     * @param Worksheet $worksheet
     * @param RedemptionStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storelink(WSMRedemptionCar $redemption, Request $request) : \Illuminate\Http\JsonResponse
    {
        $link = $this->repo->saveLink($redemption, $request->all());

        return response()->json([
            'data' => new LinkResource($link),
            'success' => 1,
            'message' => 'Ссылка добавлена'
        ]);
    }



    /**
     * ПЕРЕНЕСТИ АВТОМОБИЛЬ ОЦЕНКИ НА СКЛАД, И ЗАКРЫТЬ ОЦЕНКУ, ПРИСВОИТЬ СТАТУС НА СКЛАДЕ
     * @param Worksheet $worksheet
     * @return \Illuminate\Http\JsonResponse
     */
    public function buy(WSMRedemptionCar $redemption) : \Illuminate\Http\JsonResponse
    {
        $this->repo->buyCar($redemption);

        return response()->json([
            'data' => new RedemptionResource($redemption),
            'success' => 1,
            'message' => 'Оприходован на склад'
        ]);
    }



    /**
     * ЗАКРЫТЬ ОЦЕНКУ СО СТАТУСОМ ЗАКРЫТО, АВТОМОБИЛЬ НЕ ВЫКУПЛЕН
     * @param Worksheet $worksheet
     * @return \Illuminate\Http\JsonResponse
     */
    public function close(WSMRedemptionCar $redemption) : \Illuminate\Http\JsonResponse
    {
        $this->repo->close($redemption);

        return response()->json([
            'data' => new RedemptionResource($redemption),
            'success' => 1,
            'message' => 'Оценка завершена'
        ]);
    }



    /**
     * ПОЛУЧИТЬ СПИСОК ВСЕХ ССЫЛОК ЗАДАНОЙ ОЦЕНКИ
     * @param Worksheet $worksheet
     * @return \Illuminate\Http\JsonResponse
     */
    public function links(WSMRedemptionCar $redemption)
    {
        $links = [];

        if($redemption->apprailsal)
            $links[] = [
                'url' => $redemption->apprailsal->url(),
                'created_at' => $redemption->apprailsal->created_at,
            ];

        $other = $redemption->links->map(function($item) use ($links){
            return [
                'url' => $item->url,
                'created_at' => $item->created_at,
            ];
        })->toArray();

        $links = array_merge($links, $other);

        return response()->json([
            'data' => $links,
            'success' => 1,
        ]);
    }



    /**
     * ПОЛУЧИТЬ КОЛ-ВО ОЦЕНОК ПО ПАРАМЕТРАМ
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function counter(Request $request)
    {
        $count = $this->repo->count($request->all());

        return response()->json([
            'data' => $count,
            'success' => 1,
        ]);
    }



    /**
     * ПОЛУЧИТЬ СПИСОК КОММЕНТАИЕВ ВЫБРАННОЙ ОЦЕНКИ
     */
    public function commentList(WSMRedemptionCar $redemption)
    {
        return response()->json([
            'data' => $this->repo->getComments($redemption),
            'success' => 1,
        ]);
    }



    /**
     * Добавить комментарий в оценку
     */
    public function addComment(WSMRedemptionCar $redemption, Request $request)
    {
        $this->repo->addComment($redemption, $request->text);

        return response()->json([
            'message' => 'Комментарий добавлен',
            'success' => 1,
        ]);
    }



    /**
     * REVERT APPRAISAL
     */
    public function revert(WSMRedemptionCar $redemption)
    {
        $this->repo->revert($redemption);

        return response()->json([
            'data' => new RedemptionResource($redemption),
            'success' => 1,
            'message' => 'Оценка в работе',
        ]);
    }
}
