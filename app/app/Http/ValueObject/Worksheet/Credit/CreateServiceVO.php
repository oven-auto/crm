<?php

namespace App\Http\ValueObject\Worksheet\Credit;

Class CreateServiceVO
{
    public function __construct(
        public readonly array|null $services,
    )
    {
        
    }



    public static function fromArray(array $data)
    {
        return new self(
            services          : $data['services'] ?? null
        );
    }
}