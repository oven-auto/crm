<?php

namespace App\Classes\ORM;

use App\Classes\ORM\Interfaces\MakeStringable;

class ORMSelect implements MakeStringable
{
    private $col;

    public function __construct(array $arr)
    {
        $this->col = implode(', ', $arr);
    }



    public function makeString()
    {
        return $this->col;
    }
}