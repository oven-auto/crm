<?php

namespace App\Services\Analytic\Report;

use App\Helpers\Date\DateHelper;
use Carbon\CarbonPeriod;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

Class RealisationService
{
    private function prepareSale(array $data) : Builder
    {
        $querySale = DB::table('wsm_reserve_sales')->select([
            DB::raw('COUNT(wsm_reserve_sales.id) as _count'),
            'marks.name as _mark',
            'marks.id as mark_id',
        ])
            ->leftJoin('wsm_reserve_new_cars', 'wsm_reserve_new_cars.id', 'wsm_reserve_sales.reserve_id')
            ->leftJoin('cars', 'cars.id', 'wsm_reserve_new_cars.car_id')
            ->leftJoin('marks', 'marks.id', 'cars.mark_id')
            ->whereBetween('wsm_reserve_sales.date_at', $data['intervals'])
            ->groupBy('marks.id');

        return $querySale;
    }



    private function prepareReport(array $data) : Builder
    {
        $queryReport = DB::table('car_date_logistics')->select([
            DB::raw('COUNT(car_date_logistics.id) as _count'),
            'marks.name as _mark',
            'marks.id as mark_id',
        ])
            ->leftJoin('cars', 'cars.id', 'car_date_logistics.car_id')
            ->leftJoin('marks', 'marks.id', 'cars.mark_id')
            ->where('car_date_logistics.logistic_system_name', 'off_date')
            ->whereBetween('car_date_logistics.date_at', $data['intervals'])
            ->groupBy('marks.id');

        return $queryReport;
    }



    public function prepareTarget(array $data)
    {
        $d_1 = $data['intervals'][0];
        $d_2 = $data['intervals'][1];
        
        $period = CarbonPeriod::create($d_1, '1 month', $d_2);
        
        $months = [];
        $years = [];
        
        foreach ($period as $d)
        {   
            $months[]   = $d->month;
            $years[]    = $d->year;
        }

        $months = join(',',array_unique($months));
        $years = join(',',array_unique($years));

        $queryTarget = DB::table('targets')->select([
            DB::raw('CAST(SUM(target_marks.amount) as integer) as _count'),
            'marks.name as _mark',
            'marks.id as mark_id',
        ])
            ->leftJoin('target_marks', 'target_marks.target_id', 'targets.id')
            ->leftJoin('marks', 'marks.id', 'target_marks.mark_id')
            ->whereRaw('YEAR(targets.date_at) IN ('.$years.')')
            ->whereRaw('MONTH(targets.date_at) IN ('.$months.')')
            ->groupBy('marks.id');

        return $queryTarget;
    }



    public function prepareStock(array $data) : Builder
    {
        $queryStock = DB::table('cars')->select([
            DB::raw('COUNT(car_date_logistics.id) as _count'),
            'marks.name as _mark',
            'marks.id as mark_id',
        ])
            ->leftJoin('marks', 'marks.id', 'cars.mark_id')
            ->leftJoin('car_date_logistics', 'car_date_logistics.car_id', 'cars.id')
            ->where('car_date_logistics.logistic_system_name', 'stock_date')
            ->whereBetween(DB::raw('DATE_ADD(car_date_logistics.date_at, INTERVAL 1 SECOND)'), $data['intervals'])
            ->groupBy('marks.id');

        return $queryStock;
    }



    public function prepareTargetBrand(array $data) : Builder
    {
        $d_1 = $data['intervals'][0];
        $d_2 = $data['intervals'][1];
        
        $period = CarbonPeriod::create($d_1, '1 month', $d_2);
        
        $months = [];
        $years = [];
        
        foreach ($period as $d)
        {   
            $months[]   = $d->month;
            $years[]    = $d->year;
        }

        $months = join(',',array_unique($months));
        $years = join(',',array_unique($years));

        $queryTarget = DB::table('targets')->select([
            DB::raw('CAST(SUM(targets.amount) as integer) as _count'),
            'marks.name as _mark',
            'marks.id as mark_id',
        ])
            ->leftJoin('target_marks', 'target_marks.target_id', 'targets.id')
            ->leftJoin('marks', 'marks.id', 'target_marks.mark_id')
            ->whereRaw('YEAR(targets.date_at) IN ('.$years.')')
            ->whereRaw('MONTH(targets.date_at) IN ('.$months.')')
            ->groupBy('marks.id');

        return $queryTarget;
    }



    public function prepareMain(array $data) : Builder
    {
        $querySale              = $this->prepareSale($data);
        $queryReport            = $this->prepareReport($data);
        $queryStock             = $this->prepareStock($data);
        $queryTarget            = $this->prepareTarget($data);
        $queryTargetBrand       = $this->prepareTargetBrand($data);

        $res = DB::table('marks')->select([
            'marks.id',
            'marks.name as mark',
            'brands.name as brand',
            DB::raw('IFNULL(qSale._count, 0) as count_sale'),
            DB::raw('IFNULL(qReport._count, 0) as count_report'),
            DB::raw('IFNULL(qStock._count, 0) as count_stock'),
            DB::raw('IFNULL(qTarget._count, 0) as count_target'),
            DB::raw('IFNULL(qTargetBrand._count, 0) as count_brand'),
        ])
            ->leftJoinSub($querySale, 'qSale', 'qSale.mark_id', 'marks.id')
            ->leftJoinSub($queryReport, 'qReport', 'qReport.mark_id', 'marks.id')
            ->leftJoinSub($queryStock, 'qStock', 'qStock.mark_id', 'marks.id')
            ->leftJoinSub($queryTarget, 'qTarget', 'qTarget.mark_id', 'marks.id')
            ->leftJoinSub($queryTargetBrand, 'qTargetBrand', 'qTargetBrand.mark_id', 'marks.id')
            ->rightJoin('company_brands', 'company_brands.brand_id', 'marks.brand_id')
            ->leftJoin('brands', 'brands.id', 'marks.brand_id')
            ->where('marks.diller_status', 1);  
        
        if(isset($data['salons']))
            $res->whereIn('company_brands.company_id', $data['salons']);

        return $res;
    }



    public function getData(array $data) : Collection
    {
        $data['intervals'] = [
            DateHelper::createFromString($data['intervals'][0][0], 'd.m.Y')->setHour(0)->setMinute(0)->setSecond(0),
            DateHelper::createFromString($data['intervals'][0][1], 'd.m.Y')->setHour(23)->setMinute(59)->setSecond(59),
        ];
        
        $res = $this->prepareMain($data)->get();

        return $res;
    }
}