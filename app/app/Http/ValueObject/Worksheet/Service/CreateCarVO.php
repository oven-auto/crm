<?php

namespace App\Http\ValueObject\Worksheet\Service;

use Illuminate\Support\Arr;

class CreateCarVO
{
    public function __construct(
        public readonly string $id,
        public readonly string $type,
    )
    {
    }



    public static function fromArray(array $data)
    {
        return new self(
            id:     Arr::get($data, 'car.id'),
            type:   Arr::get($data, 'car.type'),
        );
    }
}