<?php

namespace App\Services\Worksheet;

use App\Classes\DTO\Worksheet\CreateWorksheetAction;
use App\Models\WorksheetAction;
use App\Models\WorksheetStatus;
use CreateWorksheetActionsTable;

class ActionSave
{
    private $task;
    private $action;
    private $commentService;
    private $message;

    private const COMMENT_STATUS = 1;

    /**
     * РЕИНИЦИАЛИЗАЦИЯ
     */
    private function init()
    {
        $this->action = null;
        $this->task = null;
        $this->commentService = new Comment();
    }

    /**
     * ФАСАД МЕТОД ДЛЯ СОЗДАНИЯ ДЕЙСТВИЯ РАБОЧЕГО ЛИСТА
     * @param array $data
     */
    public function saveActionFasade(Array $data) : void
    {
        $this->init();
        $this->setTask($data);
        $this->createAction($data);
        $this->setWorksheetStatus();

        $this->commentService->addSystemComment($this->action->worksheet, $this->action->getActualStatus());
        $this->commentService->addUserComment($this->action->worksheet, $data['text']);

        $this->action->fresh();
    }

    /**
     * ФАСАД МЕТОД ДЛЯ СОЗДАНИЯ ДОПОЛНИТЕЛЬНОГО КОММЕНТАРИЯ В ДЕЙСТВИИ РАБОЧЕГО ЛИСТА
     * @param array $data
     */
    public function saveCommentFasade(Array $data) : void
    {
        $this->init();
        $this->setAction($data);
        $this->commentService->addUserComment($this->action->worksheet, $data['text']);
        $this->action->load('last_comment');
    }

    public function closeActionFasade(Array $data)
    {
        $this->init();
        $this->setAction($data);
        $this->changeStatus($data);
        $this->commentService->addSystemComment($this->action->worksheet, $this->closeMessage($data));
    }

    private function closeMessage($data) {
        $status = ($data['status']);
        switch ($status) {
            case 'confirm' :
                $this->message = "Подтверждение события: {$this->action->task->name} {$this->action->begin_at->format('d.m.Y (H:i)')}";
                break;
            case 'abort' :
                $this->message = "Отмена события: {$this->action->task->name} {$this->action->begin_at->format('d.m.Y (H:i)')}";
                break;
        }
        return $this->message;
    }

    private function changeStatus($data)
    {
        $this->action->status = 'work';//$data['status'];
        // if(in_array($this->action->task->slug, ['confirm','abort']))
        //     $this->action->worksheet->fill([
        //         'status_id' => 'check'
        //     ])->save();
        $this->action->save();
    }

    /**
     * УСТАНОВИТЬ ДЕЙСТВИЕ
     * @param array $data [action_id]
     */
    private function setAction($data) : void
    {
        $this->action = WorksheetAction::findOrFail($data['action_id']);
    }

    /**
     * УСТАНОВИТЬ ТИП ЗАДАНИЯ
     * @param array $data [task_id]
     */
    private function setTask($data) :void
    {
        $this->task = \App\Models\Task::findOrFail($data['task_id']);
        $this->task->status;
    }

    /**
     * СОЗДАНИЕ ДЕЙСТВИЯ РАБОЧЕГО ЛИСТА
     * @param array $data
     */
    private function createAction($data) : void
    {
        $this->action = WorksheetAction::create([
            'worksheet_id' => $data['worksheet_id'],
            'begin_at' => $data['begin_at'],
            'end_at' => $data['end_at'],
            'task_id' => $data['task_id'],
            'author_id' => auth()->user()->id,
            'status' => $this->task->status->slug == 'work' ? 'work' : 'confirm'
        ]);
    }

    /**
     * ПОМЕНЯТЬ СТАТУС РАБОЧЕГО ЛИСТА НА ТОТ КОТОРЫЙ СОХРАНЕН В ПОСЛЕДНЕМ ДЕЙСТВИИ
     */
    private function setWorksheetStatus() : void
    {
        $this->action->worksheet->fill([
            'status_id' => $this->task->worksheet_label
        ])->save();
    }

    /**
     * ПОЛУЧИТЬ НАЗВАНИЕ ТИПА ДЕЙСТВИЯ "Контроль\Встреча\Приемка ..."
     * @return string
     */
    public function getTaskName() : string
    {
        return $this->task->status->name;
    }

    /**
     * ПОЛУЧИТЬ ДЕЙСТВИЕ
     * @return WorksheetAction
     */
    public function getAction() : WorksheetAction
    {
        return $this->action;
    }

    public function getMesage()
    {
        return $this->message;
    }
}
