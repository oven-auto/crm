<?php

namespace App\Repositories\Car\Car\DTO;

Class CarCountDTO
{
    private $array;

    public function __construct(array $data)
    {
        $this->array = $data;
    }



    public function get(string $key)
    {
        if(array_key_exists($key, $this->array))
            return $this->array[$key];
    }
}