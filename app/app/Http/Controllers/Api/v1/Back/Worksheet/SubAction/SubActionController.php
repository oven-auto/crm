<?php

namespace App\Http\Controllers\Api\v1\Back\Worksheet\SubAction;

use App\Http\Controllers\Controller;
use App\Http\Resources\Worksheet\SubAction\SubActionResource;
use App\Http\Resources\Worksheet\SubAction\SubActionUserCollection;
use App\Http\Resources\Worksheet\SubAction\SubActionListCollection;
use App\Models\SubAction;
use App\Repositories\Worksheet\SubAction\SubActionRepository;
use Illuminate\Http\Request;
use \App\Services\Comment\Comment;
use App\Services\Worksheet\WorksheetSubActionExecutorReporterService;

class SubActionController extends Controller
{
    protected $repo;
    protected $service;

    public function __construct(SubActionRepository $repo, WorksheetSubActionExecutorReporterService $service)
    {
        $this->repo = $repo;
        $this->service = $service;
    }



    /**
     * СПИСОК ВСЕХ СВЯЗАННЫХ ПОДЗАДАЧ РАБОЧЕГО ЛИСТА
     * @param int $worksheetId
     * @return SubActionListCollection
     */
    public function index(int $worksheetId) : SubActionListCollection
    {
        $subActions = $this->repo->getAllByWorksheetId($worksheetId);

        return new SubActionListCollection($subActions);
    }



    /**
     * ПОКАЗАТЬ ВЫБРАННУЮ ПОДЗАДАЧУ
     * @param SubAction $subAction
     * @return SubActionResource
     */
    public function show(SubAction $subAction) : SubActionResource
    {
        return new SubActionResource($subAction);
    }



    /**
     * СОЗДАТЬ ПОДЗАДАЧУ
     * @param SubAction $subAction
     * @param Request $request [title => string, worksheet_id => int, ?executors => array, comment => string] Надо создать Request
     * @return SubActionResource
     */
    public function store(SubAction $subAction, Request $request) : SubActionResource
    {
        $this->repo->save(subAction: $subAction, data: $request->all());

        Comment::add($subAction, 'create');

        return (new SubActionResource($subAction))->additional(['message' => 'Подзадача создана']);
    }



    /**
     * ИЗМЕНИТЬ ПОДЗАДАЧУ
     * @param SubAction $subAction
     * @param Request $request [title => string, worksheet_id => int, ?executors => array, comment => string] Надо создать Request
     * @return SubActionResource
     */
    public function update(SubAction $subAction, Request $request) : SubActionResource
    {
        $this->repo->save(subAction: $subAction, data: $request->all());

        return (new SubActionResource($subAction))->additional(['message' => 'Подзадача изменена']);;
    }



    /**
     * ЗАВЕРШИТЬ ПОДЗАДАЧУ
     * @param SubAction $subAction
     * @return SubActionResource
     */
    public function close(SubAction $subAction) : SubActionResource
    {
        $this->repo->closeAction($subAction);

        Comment::add($subAction, 'close');

        return (new SubActionResource($subAction))->additional(['message' => 'Подзадача закрыта']);;
    }



    /**
     * ДОБАВИТЬ ИСПОЛНИТЕЛЕЙ В ПОДЗАДАЧУ
     * @param SubAction $subAction
     * @param Request $request [executors => array(int, ..., int)]
     * @return SubActionUserCollection
     */
    public function append(SubAction $subAction, Request $request)
    {
        $this->service->setExecutors(subAction: $subAction, executorsArray: $request->executors);

        return (new SubActionUserCollection($subAction->executors))->additional(['message' => 'Исполнители добавлены']);
    }



    /**
     * УДАЛИТЬ ПОЛЬЗОВАТЕЛЯ ИЗ СПИСКА ИСПОЛНИТЕЛЕЙ ПОДЗАДАЧИ
     * @param SubAction $subAction
     * @param Request $request [executor => int]
     * @return SubActionUserCollection
     */
    public function remove(SubAction $subAction, Request $request)
    {
        $this->service->removeExecutor(subAction: $subAction, executorId: $request->executor);

        return (new SubActionUserCollection($subAction->executors))->additional(['message' => 'Исполнитель удален']);
    }



    /**
     * ОТЧИТАТЬСЯ ЗА ПОДЗАДАЧУ
     * @param SubAction $subAction
     * @param Request $request [reporter => int]
     * @return SubActionUserCollection
     */
    public function report(SubAction $subAction, Request $request)
    {
        $this->service->report(subAction: $subAction, reporterId: $request->reporter);

        return (new SubActionUserCollection($subAction->reporters))->additional(['message' => 'Исполнитель отчитался']);
    }



    /**
     * СНЯТЬ ОТМЕТКУ ОБ ОТЧЕТЕ
     * @param SubAction $subAction
     * @param Request $request [executor => int]
     * @return SubActionUserCollection
     */
    public function deport(SubAction $subAction, Request $request)
    {
        $this->service->deport(subAction: $subAction, reporterId: $request->reporter);

        return (new SubActionUserCollection($subAction->reporters))->additional(['message' => 'Отчет исполнителя отменен']);
    }



    /**
     * СПИСОК ВСЕХ КОММЕНТАРИЕВ ВЫБРАННОЙ ПОДЗАДАЧИ
     * @param SubAction $subAction
     * @return \Illuminate\Http\JsonResponse
     */
    public function comments(SubAction $subAction) : \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'data' => $this->repo->getSubActionComments($subAction),
            'success' => 1,
        ]);
    }
}
