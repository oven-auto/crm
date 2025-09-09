<?php

namespace App\Helpers\Number;

Class NumberHelper
{
    /**
     * СТРОГО МЕНЬШЕ 
     */
    public static function strictLess($val_1, $val_2)
    {
        if(is_null($val_1))
            return 0;
        return $val_1 < $val_2 ? 1 : 0;
    }



    /**
     * СТРОГО БОЛЬШЕ 
     */
    public static function strictGreat($val_1, $val_2)
    {
        if(is_null($val_1))
            return 0;
        return $val_1 > $val_2 ? 1 : 0;
    }



    /**
     * СТРОГО РАВНО 
     */
    public static function strictEqual($val_1, $val_2)
    {
        return $val_1 === $val_2 ? 1 : 0;
    }



    /**
     * СТРОГО БОЛЬШЕ ИЛИ РАВНО 
     */
    public static function strictGreatOrEqual($val_1, $val_2)
    {
        return self::strictEqual($val_1, $val_2) || self::strictGreat($val_1, $val_2) ? 1 : 0;
    }



    /**
     * СТРОГО МЕНЬШЕ ИЛИ РАВНО 
     */
    public static function strictLessOrEqual($val_1, $val_2)
    {
        return self::strictEqual($val_1, $val_2) || self::strictLess($val_1, $val_2) ? 1 : 0;
    }


    public static function strictComparison(int|null $val_1, string $operator, int $val_2)
    {
        return match($operator) {
            '>' => self::strictGreat($val_1, $val_2),
            '<' => self::strictLess($val_1, $val_2),
            '=' => self::strictEqual($val_1, $val_2),
            '>=' => self::strictGreatOrEqual($val_1, $val_2),
            '<=' => self::strictLessOrEqual($val_1, $val_2),
        };
    }



    public static function division($val_1, $val_2)
    {
        if(is_null($val_1))
            return 0;
        if(!$val_2)
            return 0;

        return $val_1 / $val_2;
    }
}