<?php

namespace App\Http\DTO\Client;

use App\Http\ValueObject\Client\CreateClientPassportVO;
use App\Http\ValueObject\Client\CreateClientVO;
use App\Models\Trafic;
use Illuminate\Support\Arr;

class ClientDTO
{
    public function __construct(
        public CreateClientVO $client,
        public CreateClientPassportVO $passport,
        public array $phones,
        public array|null $emails,
        public string|null $inn
    )
    {
        
    }



    public static function fromArray(array $data)
    {
        return new self(
            client: CreateClientVO::fromArray(Arr::only($data, [
                'firstname', 'lastname', 'fathername', 'trafic_sex_id', 
                'trafic_zone_id', 'company_name', 'url', 'client_type_id'
            ])),
            passport: CreateClientPassportVO::fromArray(Arr::only($data, [
                'serial_number', 'passport_issue_at', 'birthday_at', 'address',
                'driving_license', 'driver_license_issue_at', 'form_owner_id'
            ])),
            phones: Arr::get($data, 'phones', []),
            emails: Arr::get($data, 'emails') ?? [],
            inn:    Arr::get($data, 'inn') ?? null,
        );
    }



    public static function fromTrafic(Trafic $trafic)
    {
        $res = [
            'lastname'          => $trafic->client->lastname,
            'firstname'         => $trafic->client->firstname,
            'fathername'        => $trafic->client->fathername,
            'client_type_id'    => $trafic->client->client_type_id,
            'trafic_sex_id'     => $trafic->client->trafic_sex_id,
            'trafic_zone_id'    => $trafic->trafic_zone_id,
            'company_name'      => $trafic->client->company_name,
            'phones' => [
                [
                    'phone' => $trafic->client->phone,
                    'empty_phone' => $trafic->client->empty_phone,
                ],
            ],
            'emails' => [$trafic->client->email],
            'inn' => $trafic->client->inn
        ];

        return self::fromArray($res);
    }
}