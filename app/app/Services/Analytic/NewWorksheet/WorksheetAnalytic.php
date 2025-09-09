<?php

namespace App\Services\Analytic\NewWorksheet;

use App\Http\Filters\NewWorksheetAnalyticFilter;
use App\Models\Worksheet;
use App\Services\Analytic\AbstractClasses\AbstractReport;
use Illuminate\Support\Facades\DB;

Class WorksheetAnalytic extends AbstractReport
{
    private $filter;
    private $intervals;

    /**
     * Массив названий методов учавствующих в построении запроса
     * Все методы описаны ниже 
     * в обработчике для построения запроса цикл проходится по всем
     * значениям этого массива, и вызывает метод
     */
    private const FUN_NAMES = [
        'prepareCreated',
        'prepareClosed',
        'prepareResults',
    ];



    public function handle(array $intervals, array $data)
    {
        $this->intervals = $this->convertInterval($intervals);        

        $this->filter = app()->make(NewWorksheetAnalyticFilter::class, ['queryParams' => array_filter($data)]);

        $arr = array_map(function($funcName){
            return $this->makeSub(array_map(function($item) use($funcName){
                if(method_exists($this, $funcName))
                    return $this->$funcName($item,);
            }, $this->intervals));
        }, self::FUN_NAMES);
        
        $query = array_shift($arr);

        foreach($arr as $item)
            $query->unionAll($item);

        $res = $query->get()->groupBy('type');

        $res['worked'] = $this->prepareClientTypeWorked([])->get();

        $res['author'] = $this->prepareAuthors([])->get();

        return $res;
    }



    public function makeSub(array $subQ)
    {
        $query = DB::table(array_shift($subQ), 'main')
            ->select([
                DB::raw('main.name'),
                DB::raw('IF(main._count = 0, 0, 100) as proc_main'),              
                DB::raw('main.type'),
                DB::raw('main._count as count_main'),
            ]);

        foreach($subQ as $key => $subQ)
            $query
                ->addSelect([

                    DB::raw("IFNULL(sub_{$key}._count, 0) as count_$key"),
                    DB::raw("
                        CASE 
                            WHEN IFNULL(sub_{$key}._count, 0) = 0 AND main._count = 0 THEN 0
                            WHEN IFNULL(sub_{$key}._count, 0) <> 0 AND main._count <> 0 THEN ROUND((main._count / sub_{$key}._count - 1) * 100,1)
                            WHEN IFNULL(sub_{$key}._count, 0) = 0 AND main._count <> 0 THEN 100
                            WHEN IFNULL(sub_{$key}._count, 0) <> 0 AND main._count = 0 THEN -100
                        END as proc_{$key}
                    "),
                ])
                ->leftJoinSub($subQ, 'sub_'.$key, function($join) use($key){
                    $join->on('main.name', 'sub_'.$key.'.name');
                });

        return $query;
    }



    public function prepareCreated(array $interval,)
    {
        $query = Worksheet::query()
            ->select([
                DB::raw('COALESCE("Созданые в периоде") as name'),
                DB::raw('count(worksheets.id) as _count'),
                DB::raw('"created" as type')
            ])
            ->filter($this->filter)
            ->whereBetween('worksheets.created_at', $interval);
            
        return $query;
    }



    public function prepareClosed(array $interval,)
    {
        $query = Worksheet::query()
            ->select([
                DB::raw('COALESCE("Завершенные в периоде") as name'),
                DB::raw('count(worksheets.id) as _count'),
                DB::raw('"closed" as type')
            ])
            ->filter($this->filter)
            ->whereIn('worksheet_actions.status', ['confirm','abort'])
            ->whereBetween('worksheet_actions.updated_at',$interval);
            
        return $query;
    }



    public function prepareResults(array $interval,)
    {
        $query = Worksheet::query()
            ->select([
                DB::raw('COALESCE(tasks.name, "Прочие") as name'),
                DB::raw('count(worksheets.id) as _count'),
                DB::raw('"close_status" as type')
            ])
            ->filter($this->filter)
            ->whereIn('worksheet_actions.task_id', [6,7])
            ->whereBetween('worksheet_actions.updated_at',$interval)
            ->groupBy('tasks.name');
            
        return $query;
    }



    public function prepareClientTypeWorked(array $interval,)
    {
        $query = Worksheet::query()
            ->select([
                DB::raw('COALESCE(client_types.name, "Всего") as name'),
                DB::raw('count(worksheets.id) as _count'),
                DB::raw('"close_status" as type')
            ])
            ->filter($this->filter)            
            ->where('worksheets.status_id', 'work')
            ->groupBy(DB::raw('client_types.name with ROLLUP'));
            
        return $query;
    }



    public function prepareAuthors(array $interval,)
    {
        $query = Worksheet::query()
            ->select([
                DB::raw('COALESCE(concat(users.lastname, " ", users.name), "Прочие") as name'),
                DB::raw('count(worksheets.id) as _count'),
                DB::raw('"author" as type'),
                DB::raw('round(100 / (sum(count(worksheets.id)) OVER()) * count(worksheets.id), 1) as percent'),
            ])
            ->filter($this->filter)            
            ->where('worksheets.status_id', 'work')
            ->groupBy(DB::raw('concat(users.lastname, " ", users.name)'));
            
        return $query;
    }
}