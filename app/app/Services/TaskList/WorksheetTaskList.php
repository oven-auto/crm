<?php

namespace App\Services\TaskList;

use App\Http\Filters\WorksheetListFilter;
use App\Http\Filters\WorksheetSubActionFilter;
use App\Models\SubAction;
use App\Models\User;
use App\Models\Worksheet;
use App\Models\WorksheetAction;
use App\Repositories\Worksheet\DTO\TaskListSubActionDTO;
use App\Repositories\Worksheet\DTO\TaskListWorksheetDTO;

Class WorksheetTaskList 
{
    public function getUserWorksheetActionCount(User|int $user)
    {
        $worksheetCount = WorksheetAction::query()
            ->leftJoin('worksheet_executors', 'worksheet_executors.worksheet_id', 'worksheet_actions.worksheet_id')
            ->leftJoin('worksheets','worksheets.id', 'worksheet_actions.worksheet_id')
            ->where('worksheet_executors.user_id', $user)
            ->where('end_at', '<', now())
            ->where('worksheets.status_id', 'work')
            ->count() ?? 0;

        return $worksheetCount;
    }



    public function getUserWorksheetSubActionCount(User|int $user)
    {
        $subAction = SubAction::query()
            ->leftJoin('sub_action_executors', 'sub_action_executors.sub_action_id', 'sub_actions.id')
            ->where('sub_action_executors.user_id', $user)
            ->Where('sub_actions.created_at', '<', now()->addHour(1))
            ->where('sub_actions.status', 1)
            ->count() ?? 0;

        return $subAction;
    }



    /**
     * ПОЛУЧИТЬ СПИСОК РЛ ДЛЯ ЖУРНАЛА ЗАДАЧ
     */
    public function getWorksheetsForTaskList(array $data): array
    {
        $filter = app()->make(WorksheetListFilter::class, [
            'queryParams' => array_filter($data)
        ]);

        $query = Worksheet::query()
            ->with(['last_action.task', 'author', 'executors', 'company', 'structure', 'appeal', 'client.type'])
            ->filter($filter);

        $result = $query->get()->map(fn ($item) => (object)(new TaskListWorksheetDTO($item))->get())->toArray();

        return $result;
    }



    /**
     * ПОЛУЧИТЬ СПИСОК ПОДЗАДАЧ ДЛЯ ЖУРНАЛА ЗАДАЧ
     */
    public function getSubActionForTaskList(array $data): array
    {
        $filter = app()->make(WorksheetSubActionFilter::class, [
            'queryParams' => array_filter($data)
        ]);

        $query = SubAction::query()
            ->with(['worksheet.client', 'executors', 'reporters'])
            ->filter($filter);

        $result = $query->get()->map(fn ($item) => (object)(new TaskListSubActionDTO($item))->get())->toArray();

        return $result;
    }



    public function getAllActionInWorksheet(array $data)
    {
        $worksheets = $this->getWorksheetsForTaskList($data);

        $subAction = $this->getSubActionForTaskList($data);

        $collect = collect(array_merge($subAction, $worksheets));

        $merged = $collect->sortBy('sort')->values();

        return $merged;
    }
}