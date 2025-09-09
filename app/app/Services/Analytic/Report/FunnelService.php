<?php

namespace App\Services\Analytic\Report;

use App\Models\Trafic;
use App\Services\Analytic\AbstractClasses\AbstractReport;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

Class FunnelService extends AbstractReport
{
    private $intervals;

    /**
     * Массив названий методов учавствующих в построении запроса
     * Все методы описаны ниже 
     * в обработчике для построения запроса цикл проходится по всем
     * значениям этого массива, и вызывает метод
     */
    private const FUN_NAMES = [
        'queryTrafic',
        'queryWorksheet',
        'queryReserve',
        'queryContract',
        'querySale',
        'queryReport',
        'queryClosedContract',
        'queryClosedReserve',
        'queryExecutedReserve'
    ];



    public function format($result)
    {
        return $result;
    }



    public function handle(array $intervals, array $data = [])
    {
        $this->intervals = $this->convertInterval($intervals);

        $arr['company_id'] = $data['salons'] ?? null;
        $arr['appeal_id'] = 1;

        $resArr = array_map(function($funcName) use($arr){
            if(method_exists($this, $funcName))
                return $this->makeSub(array_map(function($item) use($funcName, $arr){
                    if(method_exists($this, $funcName))
                        return $this->$funcName($item, $arr);
                }, $this->intervals));
        }, self::FUN_NAMES);
        
        $query = array_shift($resArr);

        foreach($resArr as $item)
            if($item)
                $query->unionAll($item);

        $res = $query->get();

        return $res;
    }



    public function makeSub(array $subQ)
    {
        $query = DB::table(array_shift($subQ), 'main')
            ->select([            
                DB::raw('main.type'),
                DB::raw('main.name'),
                DB::raw('main._count as count_main'),
                DB::raw('IF(main._count = 0, 0, 100) as proc_main'), 
            ]);
      
        foreach($subQ as $key => $subQ)
            $query
                ->addSelect([
                    DB::raw('sub_'.$key.'._count as count_'.$key),
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
                    $join->on('main.type', 'sub_'.$key.'.type');
                });

        return $query;
    }



    /**
     * ТРАФИКИ
     */
    private function queryTrafic(array $intervals, array $data)
    {   
        $query = Trafic::select([
                DB::raw('count(trafics.id) as _count'),
                DB::raw('"trafic" as type'),
                DB::raw('"Обращения" as name'),
            ])
            ->leftJoin('trafic_clients', 'trafic_clients.trafic_id', 'trafics.id')
            ->leftJoin('companies', 'companies.id', 'trafics.company_id')
            ->leftJoin('trafic_appeals', 'trafic_appeals.id', 'trafics.trafic_appeal_id')
            ->leftJoin('appeals', 'appeals.id', 'trafic_appeals.appeal_id')
            ->onlyTarget()
            ->whereBetween('trafics.created_at', $intervals)            
            ->where('appeals.id', $data['appeal_id']);

        if($data['company_id'])
            $query->whereIn('companies.id', $data['company_id']);

        return $query;
    }



    /**
     * РАБОЧИЕ ЛИСТЫ
     */
    private function queryWorksheet(array $intervals, array $data)
    {
        $query = DB::table('worksheets')->select([
                DB::raw('count(worksheets.id) as _count'),
                DB::raw('"worksheet" as type'),
                DB::raw('"Рабочие листы" as name'),
            ])
            ->whereBetween('worksheets.created_at', $intervals)
            ->where('worksheets.appeal_id', $data['appeal_id']);
        
        if($data['company_id'])
            $query->whereIn('worksheets.company_id', $data['company_id']);

        return $query;
    }



    /**
     * РЕЗЕРВЫ
     */
    private function queryReserve(array $intervals, array $data)
    {
        $query = DB::table('wsm_reserve_new_cars')->select([
                DB::raw('count(wsm_reserve_new_cars.id) as _count'),
                DB::raw('"reserve" as type'),
                DB::raw('"Резервы" as name'),
            ])
            ->leftJoin('worksheets', 'worksheets.id', 'wsm_reserve_new_cars.worksheet_id')
            ->whereBetween('wsm_reserve_new_cars.created_at', $intervals);

        if($data['company_id'])
            $query->whereIn('worksheets.company_id', $data['company_id']);

        return $query;
    }



    /**
     * РЕЗЕРВЫ
     */
    private function queryClosedReserve(array $intervals, array $data)
    {
        $query = DB::table('wsm_reserve_new_cars')->select([
                DB::raw('count(wsm_reserve_new_cars.id) as _count'),
                DB::raw('"closed_reserve" as type'),
                DB::raw('"Резервы(только удалено)" as name'),
            ])
            ->leftJoin('worksheets', 'worksheets.id', 'wsm_reserve_new_cars.worksheet_id')
            ->whereBetween('wsm_reserve_new_cars.created_at', $intervals)
            ->whereNotNull('wsm_reserve_new_cars.deleted_at');

        if($data['company_id'])
            $query->whereIn('worksheets.company_id', $data['company_id']);

        return $query;
    }



        /**
     * РЕЗЕРВЫ
     */
    private function queryExecutedReserve(array $intervals, array $data)
    {
        $query = DB::table('wsm_reserve_new_cars')->select([
                DB::raw('count(wsm_reserve_new_cars.id) as _count'),
                DB::raw('"executed_reserve" as type'),
                DB::raw('"Резервы(только продано)" as name'),
            ])
            ->leftJoin('worksheets', 'worksheets.id', 'wsm_reserve_new_cars.worksheet_id')
            ->leftJoin('wsm_reserve_sales', 'wsm_reserve_sales.reserve_id', 'wsm_reserve_new_cars.id')

            ->whereBetween('wsm_reserve_new_cars.created_at', $intervals)
            ->whereNotNull('wsm_reserve_sales.id')
            ->whereNull('wsm_reserve_new_cars.deleted_at');

        if($data['company_id'])
            $query->whereIn('worksheets.company_id', $data['company_id']);

        return $query;
    }



    /**
     * КОНТРАКТЫ
     */
    private function queryContract(array $intervals, array $data)
    {
        $query = DB::table('wsm_reserve_new_car_contracts')->select([
                DB::raw('count(wsm_reserve_new_car_contracts.id) as _count'),
                DB::raw('"contract" as type'),
                DB::raw('"Контракты" as name'),
            ])
            ->leftJoin('wsm_reserve_new_cars', 'wsm_reserve_new_cars.id', 'wsm_reserve_new_car_contracts.reserve_id')
            ->leftJoin('worksheets', 'worksheets.id', 'wsm_reserve_new_cars.worksheet_id')
            ->whereBetween('wsm_reserve_new_car_contracts.created_at', $intervals);
        
        if($data['company_id'])
            $query->whereIn('worksheets.company_id', $data['company_id']);

        return $query;
    }



    /**
     * ЗАКРЫТЫЕ КОНТРАКТЫ
     */
    private function queryClosedContract(array $intervals, array $data)
    {
        $query = DB::table('wsm_reserve_new_car_contracts')->select([
                DB::raw('count(wsm_reserve_new_car_contracts.id) as _count'),
                DB::raw('"closed_contract" as type'),
                DB::raw('"Контракты(только расторженые)" as name'),
            ])
            ->leftJoin('wsm_reserve_new_cars', 'wsm_reserve_new_cars.id', 'wsm_reserve_new_car_contracts.reserve_id')
            ->leftJoin('worksheets', 'worksheets.id', 'wsm_reserve_new_cars.worksheet_id')
            ->whereBetween('wsm_reserve_new_car_contracts.created_at', $intervals)
            ->whereNotNull('wsm_reserve_new_car_contracts.dkp_closed_at');
        
        if($data['company_id'])
            $query->whereIn('worksheets.company_id', $data['company_id']);

        return $query;
    }



    /**
     * ПРОДАЖИ
     */
    private function querySale(array $intervals, array $data)
    {
        $query = DB::table('wsm_reserve_sales')->select([
                DB::raw('count(wsm_reserve_sales.id) as _count'),
                DB::raw('"sale" as type'),
                DB::raw('"Продажи" as name'),
            ])
            ->leftJoin('wsm_reserve_new_cars', 'wsm_reserve_new_cars.id', 'wsm_reserve_sales.reserve_id')
            ->leftJoin('worksheets', 'worksheets.id', 'wsm_reserve_new_cars.worksheet_id')
            ->whereBetween('wsm_reserve_sales.date_at', $intervals)
            ->whereNull('wsm_reserve_new_cars.deleted_at');
            
        if($data['company_id'])
            $query->whereIn('worksheets.company_id', $data['company_id']);

        return $query;
    }
}