<?php

namespace App\Services\Worksheet;

use App\Models\SubAction;
use App\Models\User;
use App\Classes\Telegram\Notice\TelegramNotice;
use App\Helpers\Array\ArrayHelper;
use \App\Services\Comment\Comment;

/**
 * КЛАСС ДЛЯ ДОБАВЛЕНИЯ В ПОДЗАДАЧУ
 * ИСПОЛНИТЕЛЕЙ И ОТЧИТАВШИХСЯ
 */
class WorksheetSubActionExecutorReporterService
{
    protected $worksheetExecutorService;

    public function __construct(WorksheetExecutorReportService $service)
    {
        $this->worksheetExecutorService = $service;
    }



    /**
     * 1 - ДОБАВИТЬ ИСПОЛНИТЕЛЕЙ В ПОДЗАДАЧУ
     * @param SubAction $subAction
     * @param array $executors [executors => array(int, ..., int)]
     * @return void
     */
    public function setExecutors(SubAction $subAction, array|int $executorsArray): void
    {
        $executorsArray = is_numeric($executorsArray) ? [$executorsArray] : $executorsArray;
        //Добавляем в участников автора по умолчанию
        //$executorsArray[] = $subAction->author_id;
        //Определяем уникальных
        $subActionUser = ArrayHelper::except($executorsArray, $subAction->executors->pluck('id')->toArray());
        //Добавляем новых учстников в подзадачу
        $subAction->executors()->attach($subActionUser);
        //Добавляем в участники рабочего листа
        $worksheet = \App\Models\Worksheet::findOrFail($subAction->worksheet_id);
        $userCollect =  \App\Models\User::whereIn('id', $subActionUser)->get();
        $this->worksheetExecutorService->attach($worksheet, $userCollect);
        //уведомлялка в телеграм всем участникам которых добавили
        TelegramNotice::run($subAction)->executor()->send(ArrayHelper::except($subActionUser, auth()->user()->id));
        //Записать комментарий
        Comment::add($subAction, 'append_executors', ['executors' => $subActionUser]);

        $subAction->load('executors');
    }



    /**
     * 2 - УДАЛИТЬ ИСПОЛНИТЕЛЯ ИЗ ПОДЗАДАЧИ
     * @param SubAction $subAction
     * @param int $executorId executor => int]
     * @return void
     */
    public function removeExecutor(SubAction $subAction, int $executorId): void
    {
        //если не автор
        if ($subAction->author_id != $executorId)
            //то удаляем из исполнителей
            $subAction->executors()->detach($executorId);
    }



    /**
     * 3 - ОТЧИТАТЬСЯ ЗА ПОДЗАДАЧУ
     * @param SubAction $subAction
     * @param int $reporterId
     * @return void
     */
    public function report(SubAction|int $subAction, int|User $reporterId): void
    {
        //если передан id то находим сущность подзадачи
        if (is_numeric($subAction))
            $subAction = SubAction::findOrFail($subAction);
        //если передан id пользователя то находим его сущность
        if (is_numeric($reporterId))
            $reporterId = User::findOrFail($reporterId);
        //Ошибка если пользователь не является участником
        if (!$subAction->executors->contains('id', $reporterId->id))
            throw new \Exception('Вы не являетесь участником задачи');
        //добавляем в список отчитавшихся
        $subAction->reporters()->attach($reporterId->id);
        //отправляем телеграм уведомлялку
        TelegramNotice::run($subAction)->report()->send([$subAction->author_id]);
        //добавляем коммент в подзадачу
        Comment::add($subAction, 'append_report', ['reporter' => $reporterId]);

        $this->removeExecutor($subAction, $reporterId->id);
    }



    /**
     * 4 - СНЯТЬ ОТМЕТКУ ОБ ОТЧЕТЕ
     * @param SubAction $subAction
     * @param int $reporterId
     * @return void
     */
    public function deport(SubAction|int $subAction, int $reporterId): void
    {
        //если передан id то находим сущность подзадачи
        if (is_numeric($subAction))
            $subAction = SubAction::findOrFail($subAction);
        //убираем из списка отчитавшихся
        $subAction->reporters()->detach($reporterId);
        //добавляем коммент
        Comment::add($subAction, 'append_report', ['reporter' => $reporterId]);
        //добавляем в список исполнителей
        $this->setExecutors($subAction, [$reporterId]);
    }
}
