<?php

namespace App\Http\ValueObject\Worksheet\Credit;

use App\Helpers\Date\DateHelper;
use Carbon\Carbon;

Class CreateContractVO
{
    public function __construct(
        public readonly Carbon|null $register_at,
        public readonly int|null $decorator_id,
    )
    {
        
    }



    public static function fromArray(array $data)
    {
        $date = null;

        if(isset($data['register_at']))
            $date = DateHelper::createFromString($data['register_at']);

        return new self(
            register_at                 : $date ?? null,       
            decorator_id                : $data['decorator'] ?? null,
        );
    }
}