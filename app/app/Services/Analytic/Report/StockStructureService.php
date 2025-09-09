<?php

namespace App\Services\Analytic\Report;

use Illuminate\Support\Facades\DB;

Class StockStructureService
{
    private const STATE_KEYS = [
        'in_stock', 'in_shipment', 'in_request',
        'in_ready', 'in_build', 'in_plan',
        'in_order', 'in_application', 'total'
    ];



    private function prepareQuery(array $data)
    {
        $query = DB::table('cars')->select([
                DB::raw('IFNULL(cars.mark_id, "total") as mark_id'),
                DB::raw('IF(cars.mark_id IS NOT NULL, marks.name, "total") as model'),
                DB::raw('IF(cars.mark_id IS NOT NULL, brands.name, "total") as brand'),
                DB::raw('CAST(SUM(IF(cars.status = "in_stock", 1, 0)) as integer) as in_stock'),
                DB::raw('CAST(SUM(IF(cars.status = "in_shipment", 1, 0)) as integer) as in_shipment'),
                DB::raw('CAST(SUM(IF(cars.status = "in_request", 1, 0))  as integer) as in_request'),
                DB::raw('CAST(SUM(IF(cars.status = "in_ready", 1, 0)) as integer) as in_ready'),
                DB::raw('CAST(SUM(IF(cars.status = "in_build", 1, 0)) as integer) as in_build'),
                DB::raw('CAST(SUM(IF(cars.status = "in_plan", 1, 0)) as integer) as in_plan'),
                DB::raw('CAST(SUM(IF(cars.status = "in_order", 1, 0)) as integer) as in_order'),
                DB::raw('CAST(SUM(IF(cars.status = "in_application", 1, 0)) as integer) as in_application'),
                DB::raw('count(cars.id) as total'),
            ])
            ->leftJoin('marks', 'marks.id', 'cars.mark_id')
            ->leftJoin('brands', 'brands.id', 'cars.brand_id')
            ->leftJoin('car_states', 'car_states.status', 'cars.status')
            ->leftJoin('car_status_types', 'car_status_types.car_id', 'cars.id')
            ->leftJoin('car_trade_markers', 'car_trade_markers.car_id', 'cars.id')

            ->whereNull('cars.deleted_at')
            ->whereIn('car_status_types.status', ['free','reserved','client'])
            ->where('car_trade_markers.trade_marker_id', 1)

            ->groupBy(DB::raw('cars.mark_id with ROLLUP'));  
        
        return $query;
    }



    private function getData(array $data)
    {
        $query = $this->prepareQuery($data);

        $res = DB::table($query)->orderBy('brand')->orderBy('model')->get();

        return $res;
    }



    private function calculate($total, $res)
    {
        $res = $res->each(function($item) use ($total){
            if($item->mark_id != 'total')
                foreach(self::STATE_KEYS as $key)
                    $item->$key = [
                        'val' => $item->$key,
                        'proc' => $total->$key ? round(100/$total->$key*$item->$key,1) : 0,
                    ];
        });

        return $res;
    }



    public function handler(array $data)
    {
        $res = $this->getData($data);

        $res->each(function($item){
            $item->total-= $item->in_application;
        });

        $total = $res->firstWhere('mark_id', 'total');

        $res = $this->calculate($total, $res)->groupBy('brand');

        return $res;
    }
}