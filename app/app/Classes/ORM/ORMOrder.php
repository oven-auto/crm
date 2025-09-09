<?php

namespace App\Classes\ORM;

use App\Classes\ORM\Interfaces\MakeStringable;

Class ORMOrder implements MakeStringable
{
    private $col;
    private $type;

    private const ORDER_TYPES = ['ASC', 'DESC'];

    public function __construct(array $arr)
    {
        $this->setCol($arr[0]);
        $this->setType($arr[1] ?? 'ASC');
    }


    private function setCol($col)
    {
        $this->col = $col;
    }



    private function setType($type)
    {
        if(in_array(mb_strtoupper($type), self::ORDER_TYPES))
            $this->type = $type;
    }



    public function makeString()
    {
        return $this->col.' '.$this->type;
    }
}