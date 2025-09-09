<?php

namespace App\Http\DTO\ServiceProduct;

use Illuminate\Support\Arr;

Class ServiceProductDTO
{
    public function __construct(
        public readonly string      $name,
        public readonly string|null      $description,
        public readonly int|null    $price,
        public readonly int|null    $duration,
        public readonly int|null    $group_id,
        public readonly array       $appeal_ids,
    )
    {
        
    }



    public static function fromArray(array $data)
    {
        return new self(
            name:           Arr::get($data, 'name'),
            description:    Arr::get($data, 'description'),
            price:          Arr::get($data, 'price'),
            duration:       Arr::get($data, 'duration'),
            group_id:       Arr::get($data, 'group_id'),
            appeal_ids:     Arr::get($data, 'appeal_ids'),
        );
    }



    public function toArray()
    {
        return (array) $this;
    }
}