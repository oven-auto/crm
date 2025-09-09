<?php

namespace App\Services\Analytic\Trafic;

use App\Models\Trafic;
use Illuminate\Support\Facades\DB;
use App\Services\Analytic\Trafic\Interfaces\TraficAnalyticInterface;
use App\Services\Analytic\Trafic\AbstractClasses\AbstractTraficAnalytic;

Class TotalTraficAnalytic extends AbstractTraficAnalytic implements TraficAnalyticInterface
{
    public function __construct()
    {
        $this->base = Trafic::query()->withTrashed();
    }
    
    
    
    protected function  prepareQuery(&$query, $filter, $period)
    {
        $subQuery = clone $query;
        
        $subQuery->select([
            DB::raw($period.' as period'),
            DB::raw('COUNT(trafics.id) as count'),
            DB::raw('"Все обращения за период" as name'),
            DB::raw('COUNT(trafics.id) as total'),
            DB::raw('IF(COUNT(trafics.id)>0, (100/COUNT(trafics.id))*COUNT(trafics.id), 0) as percent'),
            DB::raw('1 as type'),
            DB::raw('1 as border_top'),            
        ])->filter($filter);
        
        return  $subQuery;
    }
}



// public function getArrayAnalytic(array $data)
// {
//     $filter = app()->make(TraficAnalyticFilter::class, ['queryParams' => array_filter($data)]);
    
//     $query = Trafic::select([
//         DB::raw('COUNT(trafics.id) as count'),
//     ])
//     ->withTrashed()
//     ->filter($filter);
    
//     return $query->get()->map(fn($item) => [
//         'count' => $item->count ?? 0,
//         'name' => 'Все обращения за период',
//         'total' => $item->count ?? 0,
//         'percent' => $item->count ? round((100 / $item->count) * $item->count, 1) : 0,
//         'type' => $item->type ?? 1,
//         'border_top' => 1,
//     ]);
// }






