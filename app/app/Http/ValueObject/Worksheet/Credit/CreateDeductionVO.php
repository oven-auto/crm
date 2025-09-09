<?php

namespace App\Http\ValueObject\Worksheet\Credit;

Class CreateDeductionVO
{
    public function __construct(
        public readonly int|null $sum,
    )
    {
        
    }



    public static function fromArray(array $data)
    {
        return new self(
            sum          : $data['deduction'] ?? null
        );
    }
}