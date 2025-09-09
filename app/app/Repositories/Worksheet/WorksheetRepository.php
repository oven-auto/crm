<?php

namespace App\Repositories\Worksheet;

use App\Classes\LadaDNM\DNMFactory;
use App\Models\SubAction;
use App\Models\Worksheet;
use App\Models\Trafic;
use App\Repositories\Client\ClientRepository;
use App\Repositories\Worksheet\DTO\TaskListSubActionDTO;
use App\Services\Comment\Comment;
use App\Repositories\Worksheet\DTO\WorksheetCreateDTO;
use App\Repositories\Worksheet\DTO\WorksheetActionCreateDTO;
use App\Repositories\Worksheet\DTO\TaskListWorksheetDTO;
use App\Http\Filters\WorksheetListFilter;
use App\Http\Filters\WorksheetSubActionFilter;
use App\Http\Filters\WorksheetFilter;
use Illuminate\Support\Facades\DB;

/**
 * РЕПОЗИТОРИЙ РАБОЧЕГО ЛИСТА
 * - СОЗДАТЬ РЛ ИЗ ТРАФИКА
 * - [private] КОНФИГУРИРОВАНИЕ ФИЛЬТРА ПО ПАРАМЕТРАМ
 * - СПИСОК РАБОЧИХ ЛИСТОВ В ВИДЕ ПАГИНАЦИИ
 * - СПИСОК РАБОЧИХ ЛИСТОВ В ВИДЕ КОЛЛЕКЦИИ
 * - КОЛ-ВО РАБОЧИХ ЛИСТОВ, ПОДХОДЯЩИХ ПОД ПАРАМЕТРЫ ФИЛЬТРАЦИИ
 * - ПОЛУЧИТЬ КОЛ-ВО РАБОЧИХ ЛИСТОВ НАХОДЯЩИХСЯ В РАБОТЕ
 * - ПОЛУЧИТЬ СПИСОК РЛ ДЛЯ ЖУРНАЛА ЗАДАЧ
 * - ЗАКРЫТЬ РЛ
 * - ВЕРНУТЬ РЛ В РАБОТУ
 *
 * 11-09-2023
 *
 */
class WorksheetRepository
{
    /**
     * СОЗДАТЬ РЛ ИЗ ТРАФИКА
     * @param int $trafic_id
     * @return Worksheet
     */
    public function createFromTrafic(int $trafic_id): Worksheet
    {
        try {
            $result = DB::transaction(function () use ($trafic_id) {
                $trafic = Trafic::with('status')->find($trafic_id);
                
                if (!$trafic->control->begin_at)
                    throw new \Exception('Не назначена дата контроля');
                
                $client = ClientRepository::getClientFromTrafic($trafic);
               
                $worksheet = Worksheet::create((new WorksheetCreateDTO($trafic, $client))->get());

                $worksheet->last_action()->create((new WorksheetActionCreateDTO($trafic, $worksheet))->get());

                $worksheet->trafic->status;
              
                return $worksheet;
            }, 3);
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }

        return $result;
    }



    /**
     * КОНФИГУРИРОВАНИЕ ФИЛЬТРА ПО ПАРАМЕТРАМ
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function filter($query, $data = []): \Illuminate\Database\Eloquent\Builder
    {
        $query->leftJoin('worksheet_actions', function ($join) {
            $join->on('worksheet_actions.worksheet_id', 'worksheets.id');
        });

        $filter = app()->make(WorksheetFilter::class, ['queryParams' => array_filter($data)]);

        return $query->filter($filter);
    }



    /**
     * СПИСОК РАБОЧИХ ЛИСТОВ В ВИДЕ ПАГИНАЦИИ
     * @param array $data ПАРАМЕТРЫ ДЛЯ ФИЛЬТРАЦИИ
     * @param int $paginate
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function paginate(array $data, $paginate = 20): \Illuminate\Contracts\Pagination\Paginator
    {
        $query = Worksheet::query()->select('worksheets.*');

        $query->with(['last_action.task', 'author', 'executors', 'company', 'structure', 'appeal', 'client.type']);

        $this->filter($query, $data);

        $query->orderBy('worksheet_actions.end_at', 'ASC');

        $query->groupBy('worksheet_actions.end_at')->groupBy('worksheets.id');

        $result = $query->simplePaginate($paginate);

        return $result;
    }



    /**
     * СПИСОК РАБОЧИХ ЛИСТОВ В ВИДЕ КОЛЛЕКЦИИ, АККУРАТНО С ФИЛЬТРОМ, ИНАЧЕ МОЖЕТ ВЕРНУТЬ ВСЕ А ЭТО МНОГО
     * @param array $data ПАРАМЕТРЫ ДЛЯ ФИЛЬТРАЦИИ
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get(array $data): \Illuminate\Database\Eloquent\Collection
    {
        $query = Worksheet::query()->select('worksheets.*');

        $query->with(['last_action.task', 'author', 'executors', 'company', 'structure', 'appeal', 'client.type']);

        $this->filter($query, $data);

        $query->groupBy('worksheets.id')->groupBy('worksheet_actions.begin_at');

        $result = $query->orderBy('worksheet_actions.begin_at')->get();

        return $result;
    }



    /**
     * КОЛ-ВО РАБОЧИХ ЛИСТОВ, ПОДХОДЯЩИХ ПОД ПАРАМЕТРЫ ФИЛЬТРАЦИИ
     * @param array $data ПАРАМЕТРЫ ДЛЯ ФИЛЬТРАЦИИ
     * @return int
     */
    public function counter(array $data): int
    {
        $query = Worksheet::query();

        $subQuery = Worksheet::query()->select('worksheets.*');

        $this->filter($subQuery, $data);

        $query->rightJoinSub($subQuery, 'subQuery', function ($join) {

            $join->on('subQuery.id', '=', 'worksheets.id');
        });

        $result = $query->count();

        return $result;
    }



    /**
     * ПОЛУЧИТЬ КОЛ-ВО РАБОЧИХ ЛИСТОВ НАХОДЯЩИХСЯ В РАБОТЕ + ПОДХОДЯЩИХ ПОД ПАРАМЕТРЫ ФИЛЬТРАЦИИ
     * @param $data ПАРАМЕТРЫ ДЛЯ ФИЛЬТРАЦИИ
     * @return int
     */
    public function workingCount($data): int
    {
        $data['status_ids'] = ['work'];

        $count = $this->counter($data);

        return $count;
    }



    /**
     * ПОЛУЧИТЬ СПИСОК РЛ ДЛЯ ЖУРНАЛА ЗАДАЧ
     * @param array $data ПАРАМЕТРЫ ДЛЯ ФИЛЬТРАЦИИ
     * @return array
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
     * @param array $data FILTER PARAM
     * @return array
     */
    public function getSubActionForTaskList(array $data): array
    {
        $filter = app()->make(WorksheetSubActionFilter::class, [
            'queryParams' => array_filter($data)
        ]);

        $query = SubAction::query()
            ->with(['worksheet', 'executors', 'reporters'])
            ->filter($filter);

        $result = $query->get()->map(fn ($item) => (object)(new TaskListSubActionDTO($item))->get())->toArray();

        return $result;
    }



    public function getAmountList(array $data)
    {
        $worksheets = $this->getWorksheetsForTaskList($data);

        $subActions = $this->getSubActionForTaskList($data);

        $collect = collect(array_merge($worksheets, $subActions));

        $merged = $collect->sortBy('sort')->values();

        return $merged->all();
    }


    /**
     * ЗАКРЫТЬ РЛ
     * @param Worksheet $worksheet
     * @return void
     */
    public function close(Worksheet $worksheet): void
    {
        if ($worksheet->status_id != 'check')
            throw new \Exception('Команда не может быть выполнена. Закрыть можно только рабочий лист, находящийся на проверке');

        if (!in_array($worksheet->last_action->task->slug, ['confirm', 'abort']))
            throw new \Exception('Последнее действие в рабочем листе не подразумевает его закрытие. Создайте закрывающее действие.');

        $worksheet->status_id = $worksheet->last_action->task->slug;

        $worksheet->close_at = now();

        $worksheet->inspector_id = auth()->user()->id;

        $worksheet->save();

        Comment::add($worksheet->last_action, 'close');
    }



    /**
     * ВЕРНУТЬ РЛ В РАБОТУ
     * @param Worksheet $worksheet
     * @return void
     */
    public function revert(Worksheet $worksheet): void
    {
        if (in_array($worksheet->status_id, ['work']))
            throw new \Exception('Рабочий лист в работе, незачем его возвращать в работу.');

        $worksheet->status_id = 'work';
        $worksheet->inspector_id = null;
        $worksheet->close_at = null;
        $worksheet->save();

        $worksheet->last_action->fill([
            'task_id' => \App\Models\Task::where('slug', 'control')->first()->id,
            'worksheet_id' => $worksheet->id,
            'begin_at' => now(),
            'end_at' => now()->addMinutes(30),
            'author_id' => auth()->user()->id,
            'status' => 'work'
        ])->save();

        Comment::add($worksheet->last_action, 'revert');
    }
}
