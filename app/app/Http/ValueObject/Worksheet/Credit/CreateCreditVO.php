<?php

namespace App\Http\ValueObject\Worksheet\Credit;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

Class CreateCreditVO
{
    public function __construct(
        public readonly int|null $worksheet_id,
        public readonly int|null $debtor_id,
        public readonly int|null $calculation_type,
        public readonly int|null $creditor_id,
        public readonly int|null $status_id,
        public readonly int|null $author_id,
        public readonly bool|null $close,
        public readonly bool $broker_deal,
        public readonly int $creator_id,
    )
    {
        
    }



    public static function fromArray(array $data)
    {
        return new self(
            worksheet_id            : Arr::get($data, 'worksheet'), 
            debtor_id               : Arr::get($data, 'debtor'), 
            calculation_type        : Arr::get($data, 'tactic'),
            creditor_id             : Arr::get($data, 'creditor'), 
            status_id               : Arr::get($data, 'status', null),
            author_id               : Arr::get($data, 'author'),
            close                   : Arr::get($data, 'close'),  
            broker_deal             : Arr::get($data, 'broker_deal', 0),
            creator_id              : Auth::id(),
        );
    }
}