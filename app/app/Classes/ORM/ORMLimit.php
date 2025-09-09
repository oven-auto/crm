<?php

namespace App\Classes\ORM;

use App\Classes\ORM\Interfaces\MakeStringable;

class ORMLimit implements MakeStringable
{
    private $limit;

    public function __construct(array $arr)
    {
        $this->limit = $arr[0];
    }



    public function makeString()
    {
        return 'LIMIT '.$this->limit;
    }
}