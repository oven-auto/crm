<?php

namespace App\Services\Analytic\Trafic;

use App\Http\Filters\TraficFilter;
use App\Models\Trafic;
use App\Services\Analytic\Trafic\Interfaces\TraficAnalyticInterface;
use Illuminate\Support\Facades\DB;
use App\Services\Analytic\Trafic\AbstractClasses\AbstractTraficAnalytic;


Class TargetPlanTraficAnalytic extends AbstractTraficAnalytic implements TraficAnalyticInterface
{
    public  function __construct()
    {
        $this->base = Trafic::query()->onlyTarget();
    }
    
    
    
    protected function prepareQuery(&$query, $filter, $period)
    {
        $subQuery = clone $query;
        
        $subQuery->select([
            DB::raw($period.' as period'),
            DB::raw('COUNT(trafics.id)/'.now()->day * (now()->endOfMonth()->day - now()->day).' as count'),
            'total' => Trafic::select(DB::raw('count(*)'))->onlyTarget()->filter($filter),
            DB::raw('"Целевой трафик (Прогноз)" as name'),
            DB::raw('100 as percent'),
            DB::raw('0 as type'),
            DB::raw('1 as border_top'),
            
        ])->filter($filter);
        
        return  $subQuery;
    } 
}
