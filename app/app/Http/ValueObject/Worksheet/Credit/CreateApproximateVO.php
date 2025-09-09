<?php

namespace App\Http\ValueObject\Worksheet\Credit;

Class CreateApproximateVO
{
    public function __construct(
        public readonly array|null $approximates,
    )
    {
        
    }



    public static function fromArray(array $data)
    {
        return new self(
            approximates:                $data['approximates'] ?? null,       
        );
    }
}