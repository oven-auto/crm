<?php

namespace App\Http\ValueObject\Worksheet\Credit;

Class CreateCalculationVO
{
    public function __construct(
        public readonly int|null $period,
        public readonly int|null $cost,
        public readonly int|null $first_pay,
        public readonly int|null $month_pay,
        public readonly bool|null $simple,
    )
    {
        
    }



    public static function fromArray(array $data)
    {
        return new self(
            period               : $data['period'] ?? null,       
            cost                : $data['cost'] ?? null,
            first_pay           : $data['first_pay'] ?? null,
            month_pay           : $data['month_pay'] ?? null,
            simple              : $data['simple'] ?? null,
        );
    }
}