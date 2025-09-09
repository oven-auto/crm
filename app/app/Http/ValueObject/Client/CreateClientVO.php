<?php

namespace App\Http\ValueObject\Client;

class CreateClientVO
{
    public function __construct(
        public string|null $firstname,
        public string|null $lastname,
        public string|null $fathername,

        public int|null $trafic_sex_id,
        public int|null $trafic_zone_id,
        public string|null $company_name,
        public int|null $url,
        public int $client_type_id,
    )
    {
        
    }



    public static function fromArray(array $data)
    {
        return new self(
            firstname:          $data['firstname']  ?? null,
            lastname:           $data['lastname']   ?? null,
            fathername:         $data['fathername'] ?? null,
            trafic_sex_id:      $data['trafic_sex_id'] ?? null,
            trafic_zone_id:     $data['trafic_zone_id'] ?? null,
            company_name:       $data['company_name'] ?? null,
            url:                $data['url'] ?? null,
            client_type_id:     $data['client_type_id'],
        );
    }
}