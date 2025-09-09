<?php

namespace App\Classes\ORM;

use App\Classes\ORM\Interfaces\MakeStringable;

class ORMJoin implements MakeStringable
{
    private $type;
    private $table;
    private $colLeft;
    private $colRight;
    private $operand;

    private const JOIN_TYPES = ['left', 'right', 'inner'];

    public function __construct(array $arr)
    {
        $this->type         = '';
        $this->table        = $arr[0];
        $this->colLeft      = $arr[1];
        $this->colRight     = $arr[3];
        $this->operand      = $arr[2];
    }



    public function setType(string $type)
    {
        if(in_array($type, self::JOIN_TYPES))
            $this->type = $type;
    }



    public function makeString()
    {
        $arr = [
            $this->type.' JOIN',
            $this->table,
            'ON',
            $this->colLeft,
            $this->operand,
            $this->colRight
        ];

        return implode(' ', $arr);
    }
}