<?php

namespace App\Services\Analytic\Trafic;

use App\Models\Trafic;
use Illuminate\Support\Facades\DB;
use App\Services\Analytic\Trafic\Interfaces\TraficAnalyticInterface;
use App\Services\Analytic\Trafic\AbstractClasses\AbstractTraficAnalytic;

Class DeleteTraficAnalytic extends AbstractTraficAnalytic implements TraficAnalyticInterface
{
    public  function __construct() 
    {
        $this->base = Trafic::query()->onlyTrashed();
    }

    
    
    protected function  prepareQuery(&$query, $filter, $period)
    {
        $subQuery = clone $query;
        
        $subQuery->select([
            DB::raw($period.' as period'),
            DB::raw('COUNT(trafics.id) as count'),
            'total' => Trafic::select(DB::raw('count(*)'))->filter($filter)->withTrashed(),
            DB::raw('"Удаленные за период" as name'),
            DB::raw('IF(COUNT(trafics.id)>0, (100/COUNT(trafics.id))*COUNT(trafics.id), 0) as percent'),
            DB::raw('1 as type'),
            DB::raw('1 as border_top'),
            
        ])->filter($filter);
        
        return  $subQuery;
    }
}





