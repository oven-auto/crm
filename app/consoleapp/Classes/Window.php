<?php

namespace SH\Classes;

use Sh\Interfaces\HandlerInterface;
use Sh\Classes\Clearer;
use Sh\Classes\Loger;
use Sh\Classes\Emptier;

Class Window implements HandlerInterface
{
    public const OPTION = [
        'log = Посмотреть логи через TAIL',
        'empty = Очистить лог',
        'clear = Очистить консоль',
        'exit = Выход',
    ];



    public function handler()
    {
        while(true)
        {
            system("clear");

            echo 'Введи команду для продолжения:'.PHP_EOL;

            foreach(self::OPTION as $item)
                echo $item.PHP_EOL;

            $userAnswer = readline();

            if($userAnswer == 'exit')
                die();

            $factory = match($userAnswer){
                'log'   => new Loger(),
                'clear' => new Clearer(),
                'empty' => new Emptier(),
                default => new self,
            };

            $factory->handler();
        }
    }
}