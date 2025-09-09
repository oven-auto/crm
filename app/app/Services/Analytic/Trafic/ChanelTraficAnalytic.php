<?php

namespace App\Services\Analytic\Trafic;

use App\Models\Trafic;
use Illuminate\Support\Facades\DB;
use App\Services\Analytic\Trafic\Interfaces\TraficAnalyticInterface;
use App\Services\Analytic\Trafic\AbstractClasses\AbstractTraficAnalytic;

Class ChanelTraficAnalytic extends  AbstractTraficAnalytic implements TraficAnalyticInterface
{
    public function __construct() 
    {
        $this->base = Trafic::query()->withTrashed()->onlyTarget()->groupBy('trafic_chanels.id');
    }

    
    
    protected  function  prepareQuery(&$query, $filter, $period)
    {
        $subQuery = clone $query;
        
        $subQuery->select([
            DB::raw($period.' as period'),
            DB::raw('COUNT(trafics.id) as count'),
            DB::raw('IF(COUNT(trafics.id)>0, (100/COUNT(trafics.id))*COUNT(trafics.id), 0) as percent'),
            DB::raw('trafic_chanels.name as name'),
            'total' => Trafic::select(DB::raw('count(*)'))->filter($filter)->withTrashed()->onlyTarget(),
            DB::raw('trafic_chanels.id as type')
        ])->filter($filter);
        
        return  $subQuery;
    }
}


