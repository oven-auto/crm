<?php

namespace App\Services\Analytic;

use Illuminate\Support\Collection;

abstract class AbstractAnalytic
{
    protected function format($number)
    {
        return number_format($number, 1, '.', '');
    }

    
    
    protected function setPlus($number)
    {
        if($number > 0)
            return '+';
    }

    
    
    protected function getFormatStr($number)
    {
        return $this->setPlus($number).$this->format($number);
    }

    
    
    protected function getDynamic($currentCount, $periodCount)
    {
        if($currentCount != 0 && $periodCount == 0)
            return 100;
        if($currentCount == 0 && $periodCount == 0)
            return 0;

        return $periodCount ? round( (($currentCount / $periodCount) - 1) * 100, 1) : 0;
    }

    
    
    public function getArr($arr, $prevMonthCount, $prevYearCount)
    {
        $percentMonth = $this->getDynamic($arr['count'], $prevMonthCount);

        $percentYear = $this->getDynamic($arr['count'], $prevYearCount);

        return [
            'name' => $arr['name'],
            'count' => $arr['count'],
            'percent' => $this->format($arr['percent']),
            'previos_month' => $prevMonthCount,
            'month_dynamic' => $this->getFormatStr($percentMonth),
            'previos_year' => $prevYearCount,
            'year_dynamic' => $this->getFormatStr($percentYear),
            'border_top' => ($arr['border_top']) ?? 0,
            'border_bottom' => ($arr['border_bottom']) ?? 0,
            'inversion' => isset($arr['inversion']) ? $arr['inversion'] : 0
        ];
    }
    
    
    
    private function  makeList(Collection &$arr, Collection $added)
    {
        $added->each(function($item) use($arr){
            $item ? $arr->push(['type' => $item['type'], 'name' => $item['name']]) : null;
        });
    }
    
    
    
    public function toArrayWithAll($current, $prevMont, $prevYear) {
        $arr = collect();
        $this->makeList($arr, $current);
        $this->makeList($arr, $prevMont);
        $this->makeList($arr, $prevYear);        
        $arr = $arr->unique();
    
        $arr = $arr->map(function($item) use($current, $prevMont, $prevYear){
            $currentCount = $current->where('type', $item['type'])->first()['count'] ?? 0;
            $prevMonthCount = $prevMont->where('type', $item['type'])->first()['count'] ?? 0;
            $prevYearCount = $prevYear->where('type', $item['type'])->first()['count'] ?? 0;
            
            return [
                'name' => $item['name'],
                'count' => $currentCount,
                'percent' => $this->format($current->where('type', $item['type'])->first()['percent'] ?? 0),
                'previos_month' => $prevMonthCount,
                'month_dynamic' => $this->getDynamic($currentCount, $prevMonthCount),
                'previos_year' => $prevYearCount,
                'year_dynamic' => $this->getDynamic($currentCount, $prevYearCount),
            ];
        });
         
        return  $arr;
    }
}
