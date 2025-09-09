<?php

namespace App\Http\ValueObject\Worksheet\Credit;

Class CreateAwardVO
{
    public function __construct(
        public readonly int|null $sum,
        public readonly bool|null $completed,
    )
    {
        
    }



    public static function fromArray(array $data)
    {
        return new self(
            sum                : $data['award'] ?? null,       
            completed          : $data['award_complete'] ?? null,
        );
    }
}