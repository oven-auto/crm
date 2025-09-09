<?php

namespace App\Classes\Telegram;

use DateTime;
use Illuminate\Support\Facades\DB;

Class Rules
{
    private $scene;

    public function __construct(SceneInterface $scene)
    {
        $this->scene = $scene;
    }



    /**
     * ПРОВЕРКА ДЛИНЫ СТРОКИ
     * @param string $val - строка которую будем проверять
     * @param int $size - требуемая длина строки
     * @return bool
     */
    public function length(string $val, int $size) : bool
    {
        if(strlen($val) == $size)
            return 1;

        $this->scene->telegram->sendMessage($this->scene->chatId, "Длина вводимых данных {$size} символов.", $this->scene->options);

        return 0;
    }



    /**
     * ПРОВЕРКА, ЧТО СТРОКА СОСТОИТ ТОЛЬКО ИЗ ЦИФР
     * @param string $val - проверяемая строка
     * @return bool
     */
    public function numeric(string $val) : bool
    {
        if(is_numeric($val))
            return 1;

        $this->scene->telegram->sendMessage($this->scene->chatId, 'Вводимые данные должны содержать только цифры.', $this->scene->options);

        return 0;
    }



    /**
     * ПРОВЕРКА ЧТО ЗНАЧЕНИЕ СУЩЕСТВУЕТ В БАЗЕ ДАННЫХ
     * @param string $val - значение которое проверяем
     * @param string $rules - набор условий разделеных точкой (users.name), users - название таблицы, name - название столбца
     * @return bool
     */
    public function exist(string $val, $rules) : bool
    {
        $rules = explode('.', $rules);

        if(count($rules) == 2)
        {
            $table = $rules[0];
            $col = $rules[1];
            $val = substr($val, 1);
            $result = DB::table($table)->select('id')->where($col, 'LIKE', "%{$val}%")->first();

            if($result)
                return 1;

            $this->scene->telegram->sendMessage($this->scene->chatId, 'Введенные данные не существуют в CRM Сопка.', $this->scene->options);

            return 0;
        }

        $this->scene->telegram->sendMessage($this->scene->chatId, 'Программная ошибка.', $this->scene->options);

        return 0;
    }



    /**
     * ПРОВЕРКА ЧТО ВВЕДЕНОЕ ЗНАЧЕНИЕ ПРИСУТСТВУЕТ В МОДЕЛИ БАЗЫ ДАННЫХ
     * @param string $val
     * @param string $rules - users.[phone-phone^1],tg_token
     * table - users
     * column_present - phone
     * value_present_from_storage - phone^1 , ^1 - отступ от начала строки кол-во символов
     * column_needed = tg_token
     * Значение $val и column_needed связаны тем что первое это значение второе это столбец
     */
    public function present_in(string $val, string $rules)
    {
        $rules = explode('.', $rules);

        if(count($rules) < 2)
            return 0;

        $table = $rules[0];

        $collumns = explode(',', $rules[1]);

        $col_1 = $collumns[0];
        $col_1 = trim($col_1, '[');
        $col_1 = trim($col_1, ']');
        $paramArr = explode('-', $col_1);
        $col_1 = $paramArr[0];

        $param = $paramArr[1];
        $param = explode('^', $param);
        $paramName = $param[0];

        $paramText = $this->scene->getStorage($paramName);

        if(count($param) > 1)
            $paramText = substr($paramText, $param[1]);

        $col_2 = $collumns[1];

        $result = DB::table($table)
            ->select([$col_1, $col_2])
            ->where($col_1, 'LIKE', "%{$paramText}%")
            ->where($col_2, $val)
            ->first();

        if($result)
            return 1;

        $this->scene->telegram->sendMessage($this->scene->chatId, 'Код не прошёл проверку.', $this->scene->options);
        return 0;
    }



    public function date_or_current(string $val, string $format = 'd.m.Y')
    {
        if(!$val)
            $val = date($format);

        $d = DateTime::createFromFormat($format, $val);

        $res = $d && $d->format($format) == $val;

        if($res)
            return 1;

        $this->scene->telegram->sendMessage($this->scene->chatId, 'Формат даты не верен.', $this->scene->options);
        return 0;
    }



    /**
     * rule = users.tg_token=phone-phone
     */
    public function findby(string $val, string $rule)
    {
        $rules = explode('=', $rule);

        $firstArr = explode('.', $rules[0]);
        
        $first = DB::table($firstArr[0])->where($firstArr[1], $val)->first();

        if(!$first)
        {
            $this->scene->telegram->sendMessage($this->scene->chatId, 'Не удалось выполнить проверку. Проверте вводимые данные и попробуйте ещё раз.', $this->scene->options);
            return 0;
        }

        $secondArr = $firstArr = explode('-', $rules[1]);

        $col = $secondArr[0];

        $storage = $secondArr[1];

        if(mb_substr($first->$col, 1) == mb_substr($this->scene->getStorage($storage),1))
        {
            return 1;
        }

        $this->scene->telegram->sendMessage($this->scene->chatId, 'Не удалось выполнить проверку. Проверте и повторите.', $this->scene->options);
        return 0;
    }
}
