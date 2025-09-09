<?php

namespace App\Http\DTO\Credit;

use Illuminate\Support\Arr;

Class TacticCreateDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
    )
    {
        
    }



    public static function fromArray(array $data)
    {
        return new self(
            name: Arr::get($data, 'name'),
            description: Arr::get($data, 'description', ''),
        );
    }
}