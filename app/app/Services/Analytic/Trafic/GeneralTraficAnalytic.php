<?php

namespace App\Services\Analytic\Trafic;

use App\Models\Trafic;
use Illuminate\Support\Facades\DB;
use App\Services\Analytic\Trafic\Interfaces\TraficAnalyticInterface;
use App\Services\Analytic\Trafic\AbstractClasses\AbstractTraficAnalytic;

Class GeneralTraficAnalytic extends AbstractTraficAnalytic implements TraficAnalyticInterface
{
    public function __construct() 
    {
        $this->base = Trafic::query()
            ->where('trafics.trafic_status_id', '<>', 6)
            ->havingRaw('COUNT(trafic_clients.client_type_id) > 0')
            ->groupBy('client_types.id');
    }
    
    
    
    
    protected  function  prepareQuery(&$query, $filter, $period)
    {
        $subQuery = clone $query;
        
        $subQuery->select([
            DB::raw($period.' as period'),
            DB::raw('COUNT(trafic_clients.client_type_id) as count'),
            'total' => Trafic::select(DB::raw('count(*)'))->filter($filter)->withTrashed()->where('trafics.trafic_status_id', '<>', 6),
            DB::raw('IF(client_types.name IS NOT NULL, client_types.name, "Неизвестно") as name'),
            DB::raw('IF(COUNT(trafics.id)>0, (100/COUNT(trafics.id))*COUNT(trafics.id), 0) as percent'),
            DB::raw('IF(client_types.id IS NOT NULL, client_types.id, 0) as type'),
            DB::raw('1 as border_top'),  
        ])->filter($filter);
        
        return  $subQuery;
    }
}



