<?php

namespace SH\Classes;

use Sh\Interfaces\HandlerInterface;

Class Emptier extends Loger implements HandlerInterface
{
    public function handler()
    {
        $this->setFile();

        system("echo '' > {$this->file}");

        echo "Нажми ENTER для продолжения: ";
        
        $countLine = readline();
    }
}