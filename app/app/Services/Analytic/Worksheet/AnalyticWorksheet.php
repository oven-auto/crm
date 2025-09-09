<?php

namespace App\Services\Analytic\Worksheet;

use Illuminate\Support\Arr;
use App\Services\Analytic\AbstractAnalytic;
use App\Services\Analytic\Trafic\Interfaces\TraficAnalyticInterface;
use App\Services\Analytic\Worksheet\Interfaces\WorksheetAnalyticInterface;

Class AnalyticWorksheet extends AbstractAnalytic
{
    public function toArray($current, $month, $year)
    {
        $arr = [];

        foreach($current as $item)
        {
            $prevMonthCount = 0;
            $prevYearCount = 0;

            if($month->contains('type', $item['type']))
                $prevMonthCount = $month->where('type', $item['type'])->first()['count'];

            if($year->contains('type', $item['type']))
                $prevYearCount = $year->where('type', $item['type'])->first()['count'];

            $arr[] = $this->getArr($item, $prevMonthCount, $prevYearCount);
        }

        return $arr;
    }

    public function fasade($data = [], WorksheetAnalyticInterface $command)
    {
        $requestData = Arr::except($data, [
            'interval_begin', 'interval_end',
            'second_interval_begin', 'second_interval_end',
            'third_interval_begin', 'third_interval_end'
        ]);

        $neededData = [
            'interval_begin' => $data['interval_begin'],
            'interval_end' => $data['interval_end'],
        ];

        $secondData = [
            'interval_begin' => $data['second_interval_begin'],
            'interval_end' => $data['second_interval_end'],
        ];

        $thirdData = [
            'interval_begin' => $data['third_interval_begin'],
            'interval_end' => $data['third_interval_end'],
        ];

        $current        = $command->getArrayAnalytic(array_merge($neededData, $requestData));
        $prevMont       = $command->getArrayAnalytic(array_merge($secondData, $requestData));
        $prevYear       = $command->getArrayAnalytic(array_merge($thirdData,  $requestData));

        return $this->toArray($current, $prevMont, $prevYear);
    }
}
