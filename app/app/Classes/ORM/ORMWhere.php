<?php

namespace App\Classes\ORM;

use App\Classes\ORM\Interfaces\MakeStringable;

Class ORMWhere implements MakeStringable
{
    private $col;
    private $operand;
    private $val;

    public function __construct(array $arr)
    {
        $this->col = $arr[0];
        $this->operand = isset($arr[2]) ? $arr[1] : '=';
        $this->val = isset($arr[2]) ? $arr[2] : $arr[1];
    }



    public function makeString()
    {
        return $this->col . ' ' . $this->operand . ' ' .  $this->val;
    }
}