<?php

namespace App\Services\Analytic\NewTrafic;

use App\Http\Filters\TraficAnalyticFilter;
use App\Models\Trafic;
use App\Services\Analytic\AbstractClasses\AbstractReport;
use Illuminate\Support\Facades\DB;

Class TraficAnalytic extends AbstractReport
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
        'prepareDeleted',
        'prepareTotal',
        'prepareTargetTotal',
        'prepareAppeal',
        'prepareChanel',
        'prepareClientType',
        'prepareAuthor',
        'prepareManager',
        'prepareStatus'
    ];



    public function handle(array $intervals, array $data)
    {
        $this->intervals = $this->convertInterval($intervals);  

        $this->filter = app()->make(TraficAnalyticFilter::class, ['queryParams' => array_filter($data)]);

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

        return $res;
    }



    public function makeSub(array $subQ)
    {
        $query = DB::table(array_shift($subQ), 'main')
            ->select([
                DB::raw('main.name'),
                DB::raw('100 as proc_main'),              
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
                    ")
                ])
                ->leftJoinSub($subQ, 'sub_'.$key, function($join) use($key){
                    $join->on('main.name', 'sub_'.$key.'.name');
                });

        return $query;
    }
   


    /**
     * DELETED
     */
    public function prepareDeleted(array $interval,)
    {
        $query = Trafic::query()
            ->select([
                DB::raw('COALESCE("Удаленные за период") as name'),
                DB::raw('IFNULL(count(trafics.id), 0) as _count'),
                DB::raw('"deleted" as type')
            ])
            ->onlyTrashed()
            ->filter($this->filter)
            ->whereBetween('trafics.created_at', $interval);
            
        return $query;
    }



    /**
     * TOTAL
     */
    public function prepareTotal(array $interval,)
    {
        $query = Trafic::query()
            ->select([
                DB::raw('COALESCE("Все обращения за период") as name'),
                DB::raw('IFNULL(count(trafics.id), 0) as _count'),
                DB::raw('"total" as type')
            ])
            ->withTrashed()
            ->filter($this->filter)
            ->whereBetween('trafics.created_at', $interval);
        
        return $query;
    }



    /**
     * TARGET
     */
    public function prepareTargetTotal(array $interval, )
    {
        $query = Trafic::query()
            ->select([
                DB::raw('COALESCE("Целевой трафик") as name'),
                DB::raw('IFNULL(count(trafics.id), 0) as _count'),
                DB::raw('"target_total" as type')
            ])
            ->onlyTarget()
            ->filter($this->filter)
            ->whereBetween('trafics.created_at', $interval);
        
        return $query;
    }



    /**
     * APPEAL
     */
    public function prepareAppeal(array $interval, )
    {
        $query = Trafic::query()
            ->select([
                DB::raw('COALESCE(appeals.name, "Итого по цели по цели обращения") as name'),
                DB::raw('IFNULL(count(trafics.id), 0) as _count'),
                DB::raw('"appeal" as type')
            ])
            ->filter($this->filter)
            ->onlyTarget()
            ->withTrashed()
            ->whereBetween('trafics.created_at', $interval)
            ->groupBy(DB::raw('appeals.name'));
        
        return $query;
    }



    /**
     * CHANEL
     */
    public function prepareChanel(array $interval, )
    {
        $query = Trafic::query()
            ->select([
                DB::raw('COALESCE(trafic_chanels.name, "Итого по каналам") as name'),
                DB::raw('IFNULL(count(trafics.id), 0) as _count'),
                DB::raw('"chanel" as type')
            ])
            ->filter($this->filter)
            ->where('trafics.trafic_status_id', '<>', 6)
            ->withTrashed()
            ->onlyTarget()
            ->whereBetween('trafics.created_at', $interval)
            ->groupBy(DB::raw('trafic_chanels.name'));

        return $query;
    }



    /**
     * CLIENT_TYPE
     */
    public function prepareClientType(array $interval, )
    {
        $query = Trafic::query()
            ->select([
                DB::raw('COALESCE(client_types.name, "Итого по типам клиента") as name'),
                DB::raw('IFNULL(count(trafics.id), 0) as _count'),
                DB::raw('"client_type" as type')
            ])
            ->filter($this->filter)
            ->whereBetween('trafics.created_at', $interval)
            ->groupBy(DB::raw('client_types.name'));
        
        return $query;
    }



    /**
     * AUTHOR
     */
    public function prepareAuthor(array $interval, )
    {
        $query = Trafic::query()
            ->select([
                DB::raw('COALESCE(concat(users.name, " ", users.lastname), "Итого по авторам") as name'),
                DB::raw('IFNULL(count(trafics.id), 0) as _count'),
                DB::raw('"author" as type')
            ])
            ->filter($this->filter)
            ->onlyTarget()
            ->whereBetween('trafics.created_at', $interval)
            ->groupBy(DB::raw('concat(users.name, " ", users.lastname)'));

        return $query;
    }



    /**
     * MANAGER
     */
    public function prepareManager(array $interval, )
    {
        $query = Trafic::query()
            ->select([
                DB::raw('COALESCE(concat(managers.name, " ", managers.lastname), "Итого по исполнителям") as name'),
                DB::raw('IFNULL(count(trafics.id), 0) as _count'),
                DB::raw('"manager" as type')
            ])
            ->filter($this->filter)
            ->onlyTarget()
            ->whereBetween('trafics.created_at', $interval)
            ->groupBy(DB::raw('concat(managers.name, " ", managers.lastname)'));
        
        return $query;
    }



    /**
     * STATUS
     */
    public function prepareStatus(array $interval, )
    {
        $query = Trafic::query()
            ->select([
                DB::raw('COALESCE(trafic_statuses.description, "Итого по исполнителям") as name'),
                DB::raw('IFNULL(count(trafics.id), 0) as _count'),
                DB::raw('"status" as type')
            ])
            ->filter($this->filter)
            ->withTrashed()
            ->onlyTarget()
            ->whereBetween('trafics.created_at', $interval)
            ->groupBy(DB::raw('trafic_statuses.description'));
        
        return $query;
    }
}