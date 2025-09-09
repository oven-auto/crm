<?php

namespace App\Services\Analytic\Report;

use App\Helpers\Date\DateHelper;
use App\Http\Filters\ReserveNewCarFilter;
use App\Models\WsmReserveNewCar;
use App\Models\WsmReservePayment;
use Illuminate\Support\Facades\DB;

Class ReportService
{
    public function setQuery()
    {
        $query = WsmReserveNewCar::select( 
                'wsm_reserve_new_cars.*'
            )            
            ->with([
                // 'worksheet' => function($q){
                //     $q->with([
                //         'client',
                //         'author'
                //     ]);
                // }, 
                // 'car' => function($q){
                //     $q->with([
                //         'mark',
                //         'complectation.current_price',
                //         'options.current_price',
                //         'collector',
                //         'priority.sale_priority',
                //         'logistic_dates',
                //     ]);
                // },
                // 'payments',
                // 'sale',
                // 'last_comment',
                // 'lisinger',
            ]);

        return $query;
    }



    /**
     * Клиенты в работе
     */
    public function getWorkedReport(array $data)
    {   
        //TODO Поправить работы аналитики КЛИЕНЫ В РАБОТЕ
        $data = [
            'logistic_statuses' => ['in_stock'], //на складе
            'type_statuses' => ['reserved', 'client'], //только клиентские и резерв
            'has_debit' => 1, //у которых долг > 0
            'has_paid_date' => 0
        ];

        $filter = app()->make(ReserveNewCarFilter::class, ['queryParams' => $data]);

        $query = $this->setQuery();

        $query->filter($filter);

        $sales = $query->get();
        
        return $sales;
    }



    /**
     * План поступлений
     */
    public function getPlannedReport(array $data)
    {
        $data = [
            'type_statuses' => ['reserved', 'client'], //только клиентские и резерв
            'has_debit' => 1, //у которых долг > 0
            'has_paid_date' => 1
        ];

        $filter = app()->make(ReserveNewCarFilter::class, ['queryParams' => $data]);

        $query = $this->setQuery();

        $query->filter($filter);

        $sales = $query->get();
        
        return $sales;
    }



    /**
     * Выдача с долгом
     */
    public function getWithDebitReport(array $data)
    {
        $data = [
            'type_statuses' => ['issued', 'saled'], //только клиентские и резерв
            'has_debit' => 1 //у которых долг > 0
        ];

        $filter = app()->make(ReserveNewCarFilter::class, ['queryParams' => $data]);

        $query = $this->setQuery();

        $query->filter($filter);

        $sales = $query->get();
        
        return $sales;
    }



    /**
     * Полные оплаты
     */
    public function getPaidReport(array $data)
    {
        $data = [
            'type_statuses' => ['client'], //только клиентские и резерв
            'has_debit' => 0 //у которых долг > 0
        ];

        $filter = app()->make(ReserveNewCarFilter::class, ['queryParams' => $data]);

        $query = $this->setQuery();

        $query->filter($filter);

        $sales = $query->get();
        
        return $sales;
    }



    /**
     * Выдачи
     */
    public function getIssuedReport(array $data)
    {
        $data = [
            'type_statuses' => ['issued'], //только выданные
        ];
        
        $filter = app()->make(ReserveNewCarFilter::class, ['queryParams' => $data]);

        $query = $this->setQuery();

        $query->filter($filter);

        $sales = $query->get();
        
        return $sales;
    }



    /**
     * Продажи
     */
    public function getSaledReport(array $data)
    {
        $data = [
            'type_statuses' => ['saled'], 
            'sale_date' => $data['intervals'][0],
        ];
        
        $filter = app()->make(ReserveNewCarFilter::class, ['queryParams' => $data]);

        $query = $this->setQuery();

        $query->filter($filter);

        $sales = $query->get();
        
        return $sales;
    }



    /**
     * Выручка
     */
    public function getReceiptReport(array $data)
    {
        $intervals = $data['intervals'][0];
        
        $query = WsmReservePayment::query()
            ->with([
                'payment', 
                'reserve' => function($q){
                    $q->with([
                        // 'worksheet' => function($qW){
                        //     $qW->with([
                        //             'client',
                        //             'author'
                        //         ]);
                        // }, 
                        // 'car' => function($qC){
                        //     $qC->with([
                        //         'mark',
                        //         'complectation.current_price',
                        //         'options.current_price',
                        //         'collector',
                        //         'priority.sale_priority',
                        //         'logistic_dates',
                        //     ]);
                        // },
                        //'payments',
                        //'sale',
                        //'last_comment',
                        //'lisinger',
                    ]);
                },
            ]);         

        $reseipts = $query->whereBetween(DB::raw('DATE_ADD(wsm_reserve_payments.date_at, INTERVAL 1 SECOND)'), [
            DateHelper::createFromString($intervals[0])->setHour(0)->setMinute(0)->setSecond(0), 
            DateHelper::createFromString($intervals[1] ?? $intervals[0])->setHour(23)->setMinute(59)->setSecond(59)
        ])
            ->leftJoin('wsm_reserve_new_cars', 'wsm_reserve_new_cars.id', 'wsm_reserve_payments.reserve_id')
            ->leftJoin('cars', 'cars.id', 'wsm_reserve_new_cars.car_id')
            ->whereNotNull('cars.id')
            ->groupBy('wsm_reserve_payments.id')
            ->get();
         
        //$res = $reseipts->each(function($itemReserve) use ($intervals){
            //$itemReserve->payments = $itemReserve->payments->whereBetween('date_at', $intervals)->all();
            // $pays = $itemReserve->payments->whereBetween('date_at', $intervals);

            // $res = $pays->each(function($itemPay) use($itemReserve){
            //     $obj = $itemReserve;
            //     $obj->payments = $itemPay;
            //     return $obj;
            // });

            //return $res;
        //});
        //dd($res);
        return $reseipts;
    }
}