<?php

namespace App\Http\DTO\Car\Color;

use Illuminate\Support\Arr;

class CreateColorDTO
{
    public function __construct(
        public readonly int $brand_id,
        public readonly int $mark_id,
        public readonly int $base_id,
        public readonly string $name,
    )
    {

    }



    public static function fromArray(array $data)
    {
        return new self(
            brand_id: Arr::get($data,'brand_id'),
            mark_id: Arr::get($data,'mark_id'),
            base_id: Arr::get($data, 'base_id'),
            name: Arr::get($data, 'name'),
        );
    }
}