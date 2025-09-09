<?php

namespace App\Services\Analytic\AbstractClasses;

use Carbon\Carbon;

abstract class AbstractReport
{
    abstract public function handle(array $intervals, array $data);

    public function convertInterval(array $interval)
    {
        $arr = [];
        
        foreach($interval as $item)
        {
            $date_1 = Carbon::createFromFormat('d.m.Y', $item[0])
                ->setHour(0)
                ->setMinute(0)
                ->setSecond(0);

            $date_2 = isset($item[1]) ? Carbon::createFromFormat('d.m.Y', $item[1]) : $date_1;
            $date_2->setHour(23)->setMinute(59)->setSecond(59);

            $arr[] = [$date_1->format('Y-m-d H:i:s'), $date_2->format('Y-m-d H:i:s')];
        }
        
        return $arr;
    }
}