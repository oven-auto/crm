<?php

namespace App\Http\ValueObject\Worksheet\Service;

use App\Helpers\Date\DateHelper;

Class CreateContractVO
{
    public function __construct(
        public readonly string|null $number,
        public readonly string|null $begin_at,
        public readonly string|null $register_at,
        public readonly int|null $decorator_id,
        public readonly int|null $manager_id,
    )
    {
        
    }



    public static function fromArray(array $data)
    {
        $begin = null;

        if(isset($data['begin_at']))    
            $begin = DateHelper::createFromString($data['begin_at']);

        $register = null;

        if(isset($data['register_at']))    
            $register = DateHelper::createFromString($data['register_at']);

        return new self(
            number             : $data['number'] ?? null,
            begin_at           : $begin,
            register_at        : $register,
            decorator_id       : $data['decorator'] ?? null,         
            manager_id         : $data['manager'] ?? null,
        );
    }
}