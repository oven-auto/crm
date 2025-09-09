<?php

namespace App\Helpers\Array;

Class ArrayHelper
{
    /**
     * УДАЛИТЬ ИЗ ОДНОМЕРНОГО МАССИВА ПО ЗНАЧЕНИЮ
     * @param array $data Массив в которм хотим что-то удалить
     * @param int|array Значение которого не должно быть в массиве $data
     */
    public static function except(array $data, int|array $deleted) : array
    {
        if(is_numeric($deleted))
            $deleted =  [$deleted];

        $result = array_diff($data, $deleted);

        return $result;
    }



    public static function isAllNull(array $data) : bool
    {
        $result = array_filter($data, function($item) {
            return is_null($item) ? 0 : 1;
        });

        return count($result) ? 0 : 1;
    }



    public static function getOnlyNotNullable(array $data)
    {
        $result = array_filter($data, function($item) {
            return is_null($item) ? 0 : 1;
        });

        return $result;
    }
}
