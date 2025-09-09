<?php

namespace App\Http\DTO\Car\Collector;

use Illuminate\Support\Arr;

Class CreateCarCollectorDTO
{
    public function __construct(
        public readonly string $name
    )
    {
        
    }


    
    public function getAsArray() : array
    {
        return (array) $this;
    }



    public static function fromArray(array $data) : self
    {
        return new self(
            name: Arr::get($data, 'name'),
        );
    }
}