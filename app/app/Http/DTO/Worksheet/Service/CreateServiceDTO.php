<?php

namespace App\Http\DTO\Worksheet\Service;

use App\Http\ValueObject\Worksheet\Service\CreateAwardVO;
use App\Http\ValueObject\Worksheet\Service\CreateCarVO;
use App\Http\ValueObject\Worksheet\Service\CreateContractVO;
use App\Http\ValueObject\Worksheet\Service\CreateDeductionVO;
use App\Http\ValueObject\Worksheet\Service\CreateServiceVO;
use Illuminate\Support\Arr;

class CreateServiceDTO
{
    public function __construct(
        public CreateServiceVO $service,
        public CreateAwardVO $award,
        public CreateContractVO $contract,
        public CreateDeductionVO $deduction,
        public CreateCarVO $car
    )
    {
        
    }


    public static function fromArray(array $data) : self
    {    
        return new self(
            service: CreateServiceVO::fromArray(Arr::only($data, ['worksheet', 'service', 'payment', 'provider', 'cost', 'simple', 'close'])),
            award: CreateAwardVO::fromArray(Arr::only($data, ['award', 'award_complete'])),
            contract: CreateContractVO::fromArray(Arr::only($data, ['number', 'begin_at', 'register_at', 'decorator', 'manager'])),
            deduction: CreateDeductionVO::fromArray(Arr::only($data, ['deduction'])),
            car: CreateCarVO::fromArray($data)
        );
    }
}