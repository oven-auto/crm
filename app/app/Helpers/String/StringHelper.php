<?php

namespace App\Helpers\String;

class StringHelper
{
    public static function onlyLettersAndNumbers(string $str)
    {
        return preg_replace("/[^,.0-9]/", '', $str);
    }



    public static function phoneMask($value)
    {
        $phone = '';

        if ($value)
            $phone = sprintf(
                "+%s (%s) %s-%s-%s",
                mb_substr($value, 0, 1),
                mb_substr($value, 1, 3),
                mb_substr($value, 4, 3),
                mb_substr($value, 7, 2),
                mb_substr($value, 9)
            );
        return $phone;
    }



    public static function moneyMask($value = null, $valute = 'р.', $sign = false)
    {
        $signVal = '';

        if (!$value)
            $value = 0;

        if ($sign)
            $signVal = $value > 0 ? '+' : '';

        return $signVal . number_format($value, 0, '', ' ') . $valute;
    }



    /**
     * ОБЕРНУТЬ СТРОКУ
     * СИМВОЛЫ ДОЛЖНЫ БЫТЬ ПАРНЫМИ, ОБЕРТКА ДЕЛИТСЯ ПОПОЛАМ, ПЕРВАЯ ПОЛОВИНА В НАЧАЛО, ВТОРАЯ ПОДСТАВИТСЯ В КОНЕЦ
     */
    public static function strWrap(string|null $value, $wrap = '()')
    {
        if (!$value)
            return;

        $length = mb_strlen($wrap);

        $wrap_1 = mb_strcut($wrap, 0, intdiv($length, 2));

        $wrap_2 = mb_strcut($wrap, intdiv($length, 2), $length);

        return $wrap_1 . $value . $wrap_2;
    }



    /**
     * ПОКАЗАТЬ ЗНАК + ЧИСЛА
     */
    public function getSignVal(int|string|float $value)
    {
        return $value > 0 ? '+' : '';
    }



    /**
     * DAYS
     */
    public static function dayWord($countDay)
    {
        $diff = $countDay % 10;

        if ($countDay > 10 && $countDay < 20)
            return 'дней';
        elseif ($diff == 1)
            return 'день';
        elseif ($diff > 1 && $diff < 5)
            return 'дня';
        else
            return 'дней';
    }



    /**
     * Array to string
     */
    public static function arrayToStr(array $arr)
    {
        return join(', ', $arr);
    }

}
