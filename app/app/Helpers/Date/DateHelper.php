<?php

namespace App\Helpers\Date;

use Carbon\Carbon;
use Symfony\Component\Mime\Header\DateHeader;

class DateHelper
{
    public static function addYear($date, $year = 1)
    {
        return date('Y-m-d H:i:s', strtotime("+$year year", strtotime($date)));
    }



    public static function addMonth($date, $month = 1)
    {
        return date('Y-m-d H:i:s', strtotime("+$month month", strtotime($date)));
    }



    public static function addDay($date, $day = 1)
    {
        return date('Y-m-d H:i:s', strtotime("+$day day", strtotime($date)));
    }



    public static function addWeek($date, $week = 1)
    {
        return date('Y-m-d H:i:s', strtotime("+$week week", strtotime($date)));
    }



    public static function format($date, $format = 'd.m.Y')
    {
        return date($format, strtotime($date));
    }



    public static function russianMonth($index)
    {
        $arr = ['янв', 'февр', 'март', 'апр.', 'май', 'июнь', 'июль', 'авг', 'сент', 'окт', 'нояб', 'дек'];
        return $arr[$index];
    }



    /**
     * СОЗДАТЬ ОБЪЕКТ КАРБОН ИЗ СТРОКИ НЕОБХОДИМОГО ФОРМАТА
     * @param $string
     * @param $format
     * @return Carbon
     */
    public static function createFromString($string, $format = 'd.m.Y'): Carbon|null
    {   
        if($string)
            return Carbon::createFromFormat($format, $string);

        return null;
    }



    public static function getFormatedDate(string|null $date = null, string $in = 'd.m.Y', $out = 'd.m.Y')
    {
        if(!$date)
            return NULL;
        return Carbon::createFromFormat($in, $date)->format($out);
    }
    
    
    
    public static function isIntervalCurrentMonth($date_1, $date_2) 
    {
        $firstDayOfMonth = now()->startOfMonth();
        $lastDayOdMonth = now()->endOfMonth();
        
        $date_1 = DateHelper::createFromString($date_1);
        $date_2 = DateHelper::createFromString($date_2);
        
        if($firstDayOfMonth->diff($date_1)->days == 0 && $lastDayOdMonth->diff($date_2)->days == 0)
            return  1;
        return 0;
    }



    public static function setDateToCarbon(array $dates, $inFormat = 'd.m.Y')
    {
        $date_1 = Carbon::createFromFormat('d.m.Y', $dates[0]);
        $date_2 = isset($dates[1]) ? Carbon::createFromFormat('d.m.Y', $dates[1]) : $date_1;
        $date_1->setHour(0)->setMinute(0)->setSecond(0);
        $date_2->setHour(23)->setMinute(59)->setSecond(59);

        return [$date_1, $date_2];
    }
}






