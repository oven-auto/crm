<?php

namespace App\Http\DTO\Worksheet\Credit;

use App\Http\ValueObject\Worksheet\Credit\CreateApproximateVO;
use App\Http\ValueObject\Worksheet\Credit\CreateAwardVO;
use App\Http\ValueObject\Worksheet\Credit\CreateCalculationVO;
use App\Http\ValueObject\Worksheet\Credit\CreateContractVO;
use App\Http\ValueObject\Worksheet\Credit\CreateCreditVO;
use App\Http\ValueObject\Worksheet\Credit\CreateDeductionVO;
use App\Http\ValueObject\Worksheet\Credit\CreateServiceVO;
use App\Http\ValueObject\Worksheet\Service\CreateCarVO;
use Illuminate\Support\Arr;

Class CreateCreditDTO
{
    public function __construct(
        public readonly CreateCreditVO $credit,
        public readonly CreateApproximateVO $approximates,
        public readonly CreateAwardVO $award,
        public readonly CreateServiceVO $services,
        public readonly CreateContractVO $contract,
        public readonly CreateCalculationVO $calculation,
        public readonly CreateDeductionVO $deduction,
        public readonly CreateCarVO $car,
        public readonly array $content,
    )
    {
        
    }



    public static function fromArray(array $data)
    {
        return new self(
            credit: CreateCreditVO::fromArray(Arr::only($data, [
                'worksheet', 'debtor', 'tactic', 'creditor', 'status', 'author', 'close', 'broker_deal'
            ])),
            approximates: CreateApproximateVO::fromArray(Arr::only($data, ['approximates'])),
            services: CreateServiceVO::fromArray(Arr::only($data, ['services'])),
            award: CreateAwardVO::fromArray(Arr::only($data, ['award', 'award_complete'])),
            contract: CreateContractVO::fromArray(Arr::only($data, ['register_at', 'decorator'])),
            calculation: CreateCalculationVO::fromArray(Arr::only($data, ['period', 'cost','first_pay','month_pay','simple'])),
            deduction: CreateDeductionVO::fromArray(Arr::only($data,['deduction'])),
            car: CreateCarVO::fromArray($data),
            content: Arr::get($data, 'content', []),
        );
    }
}