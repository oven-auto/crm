<?php

namespace SH\Classes;

use Sh\Interfaces\HandlerInterface;

Class Loger implements HandlerInterface
{
    public $file;
    public $countLine;
    public $path = __DIR__ . '/../../storage/logs';



    public function setFile()
    {
        echo 'Введи номер фаила для продолжения:'.PHP_EOL;
    
        $files = (scandir($this->path));
    
        $newFiles = array();
    
        foreach($files as $item)
            if(preg_match('/.log/',$item))
                array_push($newFiles, $item);
    
        foreach($newFiles as $key => $item)
            echo ($key+1). ' - '. $item . PHP_EOL;
    
        $fileNumber = readline();

        $this->file = $this->path.'/'.$newFiles[$fileNumber-1];
    }



    public function setLine()
    {
        echo 'Укажи количество строк, которое хочешь увидеть (Tail работает с конца): ';
    
        $this->countLine = readline();
    }



    public function handler()
    {
        $this->setFile();

        $this->setLine(); 
    
        exec("tail -n {$this->countLine} {$this->file}", $log);
    
        foreach($log as $item)
            echo $item.PHP_EOL;

        echo "Нажми ENTER для продолжения: ";
        
        $countLine = readline();
    }
}