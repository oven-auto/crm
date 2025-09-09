<?php

namespace App\Services\Analytic\Trafic;

use App\Http\Filters\TraficAnalyticFilter;
use App\Models\Trafic;
use App\Models\TraficStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\Analytic\Trafic\Interfaces\TraficAnalyticInterface;
use App\Services\Analytic\Trafic\AbstractClasses\AbstractTraficAnalytic;

Class TargetTraficAnalytic extends AbstractTraficAnalytic implements TraficAnalyticInterface
{
    public function __construct() 
    {
        $this->base = Trafic::query()
            ->withTrashed()
            ->onlyTarget()
            ->groupBy('trafics.trafic_status_id');
    }
    
    
    
    protected  function  prepareQuery(&$query, $filter, $period)
    {
        $subQuery = clone $query;
        
        $subQuery->select([
            DB::raw($period.' as period'),
            DB::raw('COUNT(trafics.trafic_status_id) as count'),
            DB::raw('IF(trafic_statuses.id IS NOT NULL, trafic_statuses.description, "Неизвестно") as name'),
            'total' => Trafic::select(DB::raw('count(*)'))->filter($filter)->withTrashed()->onlyTarget(),
            DB::raw('IF(COUNT(trafics.trafic_status_id)>0, (100/COUNT(trafics.trafic_status_id))*COUNT(trafics.trafic_status_id), 0) as percent'),
            DB::raw('IF(trafics.trafic_status_id IS NOT NULL, trafics.trafic_status_id, 0) as type'),
            DB::raw('1 as border_top'),
            
        ])->filter($filter);
        
        return  $subQuery;
    }
}



//     public function getArrayAnalytic($data = [])
//     {
//         $filter = app()->make(TraficAnalyticFilter::class, ['queryParams' => array_filter($data)]);

//         $subQuery = Trafic::select([
//                 'trafics.trafic_status_id',
//                 DB::raw('COUNT(trafics.trafic_status_id) as count'),
//                 'total' => Trafic::select(DB::raw('count(*)'))
//                     ->filter($filter)
//                     ->withTrashed()
//                     ->onlyTarget()
//             ])
//             ->withTrashed()
//             ->onlyTarget()
//             ->filter($filter)
//             ->groupBy('trafics.trafic_status_id');

//         $query = TraficStatus::select('trafic_statuses.description as name', 'subQuery.count', 'subQuery.total', DB::raw('trafic_statuses.id as type'))
//             ->leftJoinSub($subQuery, 'subQuery', function($join){
//                 $join->on('subQuery.trafic_status_id','=','trafic_statuses.id');
//             })->whereIn('trafic_statuses.id', [1,2,3,4]);

//         $result = $query->get()->map(fn($item) => [
//             'count' => $item->count ?? 0,
//             'name' => $item->name,
//             'total' => $item->total ?? 0,
//             'percent' => $item->total ? round((100 / $item->total) * $item->count, 2) : 0,
//             'type' => $item->type,
//             'border_bottom' => 0,
//             'inversion' => $item->type == 4 ? 1 : 0
//         ]);

//         return $result;
//     }









