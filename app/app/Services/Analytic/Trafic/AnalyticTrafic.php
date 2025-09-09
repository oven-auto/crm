<?php

namespace App\Services\Analytic\Trafic;

use Illuminate\Support\Arr;
use Carbon\Carbon;
use App\Helpers\Date\DateHelper;
use App\Services\Analytic\AbstractAnalytic;
use App\Services\Analytic\Trafic\Interfaces\TraficAnalyticInterface;

Class AnalyticTrafic extends AbstractAnalytic
{
    public function isShowMonth(array $data)
    {
        if(isset($data['show_month']))
            return 1;
        return 0;
    }
    
    
    
    public function toArray($current, $month, $year)
    {
        $arr = [];

        foreach($current as $item)
        {
            $prevMonthCount = $month->where('type', $item['type'])->first()['count'] ?? 0;
            $prevYearCount = $year->where('type', $item['type'])->first()['count'] ?? 0;

            $arr[] = $this->getArr($item, $prevMonthCount, $prevYearCount);
        }

        return $arr;
    }
    
    
    
    public function prepareData($data = [])
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
        
        return ([
            'requestData' => $requestData, 
            'neededData' => $neededData, 
            'secondData' => $secondData, 
            'thirdData' => $thirdData
        ]);
    }

    
    
    public function analytics(array $data, TraficAnalyticInterface $command, $planed = false) 
    {
        extract($this->prepareData($data));
        
        $result = $command->analytics([
            array_merge($neededData,  $requestData),
            array_merge($secondData,  $requestData),
            array_merge($thirdData,   $requestData),
        ]);
        
        // if($planed == true && $command instanceof TargetTotalTraficAnalytic)
        // {   
        //     $isCurMonth = DateHelper::isIntervalCurrentMonth(
        //         $neededData['interval_begin'], 
        //         $neededData['interval_end']
        //     );
            
        //     if($isCurMonth)
        //         $this->appendPlaned($result[1]);
        // }
        
        return  $this->toArrayWithAll($result[1], $result[2], $result[3]);
    }
    
    
    
    // public function targetWithPlan(array $data = [], TraficAnalyticInterface $command) 
    // {
    //     extract($this->prepareData($data));
        
    //     $current        = $command->getArrayAnalytic(array_merge($neededData, $requestData));
    //     $prevMont       = $command->getArrayAnalytic(array_merge($secondData, $requestData));
    //     $prevYear       = $command->getArrayAnalytic(array_merge($thirdData,  $requestData));
        
    //     return  $this->toArrayWithAll($current, $prevMont, $prevYear);
    // }
    
    
    
    private function appendPlaned(&$current)
    {
        $cur = $current->first();
        $daysInMonth =  now()->endOfMonth()->day;
        $nowNumber = now()->day;
        $currentCount = $cur['count'];
        
        $current->push([
            "count" => ceil($currentCount/$nowNumber*($daysInMonth-$nowNumber)),
            "name" => "Планируемый целевой трафик!",
            "total" => ceil($currentCount/$nowNumber*($daysInMonth-$nowNumber)),
            "percent" => 100,
            "type" => 1,
            "border_top" => 1,
            "border_bottom" => 1,
        ]);
    }
}



// public function fasade(array $data = [], TraficAnalyticInterface $command)
// {
//     extract($this->prepareData($data));
    
//     $current        = $command->getArrayAnalytic(array_merge($neededData, $requestData));
//     $prevMont       = $command->getArrayAnalytic(array_merge($secondData, $requestData));
//     $prevYear       = $command->getArrayAnalytic(array_merge($thirdData,  $requestData));
    
//     return $this->toArray($current, $prevMont, $prevYear);
// }



// public function all(array $data = [], TraficAnalyticInterface $command)
// {
//     extract($this->prepareData($data));
    
//     $current        = $command->getArrayAnalytic(array_merge($neededData, $requestData));
//     $prevMont       = $command->getArrayAnalytic(array_merge($secondData, $requestData));
//     $prevYear       = $command->getArrayAnalytic(array_merge($thirdData,  $requestData));
    
//     return  $this->toArrayWithAll($current, $prevMont, $prevYear);
// }



