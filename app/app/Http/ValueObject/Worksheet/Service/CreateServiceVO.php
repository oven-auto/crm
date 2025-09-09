<?php

namespace App\Http\ValueObject\Worksheet\Service;

Class CreateServiceVO
{
    public function __construct(
        public readonly int $worksheet_id,
        public readonly int $service_id,
        public readonly int|null $provider_id,
        public readonly int $payment_id,
        public readonly int $cost,
        public readonly bool $simple,
        public readonly bool $close,
    )
    {
        
    }



    public static function fromArray(array $data) :self
    {
        return new self(
            worksheet_id       : $data['worksheet'],
            service_id         : $data['service'],
            provider_id        : $data['provider'] ?? null,
            payment_id         : $data['payment'],
            cost               : $data['cost'] ?? 0,
            simple             : $data['simple'] ?? 0,   
            close              : $data['close'] ?? 0, 
        );
    }
}