<?php

namespace App\Classes\System;

Class TimeControl
{
    private static $state;

    private function __construct()
    {
        
    }



    public static function remember($key)
    {
        $time = microtime(true);
        
        if(isset(self::$state[$key]))
            self::$state[$key]['end'] = $time;
        else
            self::$state[$key]['begin'] = $time;
    }



    public static function get($key)
    {
        $res = isset(self::$state[$key]['end']) ? self::$state[$key]['end'] - self::$state[$key]['begin'] : null;

        return round($res,2) ?? null;
    }
}