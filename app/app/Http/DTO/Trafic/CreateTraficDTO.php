<?php

namespace App\Http\DTO\Trafic;

use App\Helpers\Date\DateHelper;
use Carbon\Carbon;
use Illuminate\Support\Arr;

Class CreateTraficDTO
{
    public function __construct(
            public readonly int|null $trafic_brand_id ,
            public readonly int|null $trafic_section_id,
            public readonly int|null $trafic_appeal_id,
            public readonly Carbon|null $begin_at,
            public readonly string|null $comment,
            public readonly string|null $created_at,
            public readonly string|null $email,
            public readonly Carbon|null $end_at,
            public readonly string|null $fathername,
            public readonly string|null $firstname,
            public readonly string|null $lastname,
            public readonly int|null $person_type_id,
            public readonly string|null $phone,
            public readonly string|null $time,               
            public readonly int|null $trafic_chanel_id,
            public readonly int|null $trafic_interval,
            public readonly int|null $manager_id,
            public readonly int|null $trafic_sex_id ,
            public readonly int|null $trafic_zone_id,
            public readonly array|null $trafic_need_id,
            public readonly string|null $inn,
            public readonly int|null $client_type_id,
            public readonly string|null $company_name,

    )
    {

    }



    public static function fromArray(array $data)
    {
        if(isset($data['trafic_need_id']) && is_array($data['trafic_need_id']))
        {
            $arr = array_map(function($item){
                return ['trafic_product_number' => $item['id']];
            }, $data['trafic_need_id']);

            $data['trafic_need_id'] = $arr;
        }

        return new self(            
            comment: Arr::get($data, 'comment', null),
            created_at: Arr::get($data, 'created_at', null),

            end_at: DateHelper::createFromString(Arr::get($data, 'end_at', null), 'd.m.Y H:i', ) ?? null,
            begin_at: DateHelper::createFromString(Arr::get($data, 'begin_at', null), 'd.m.Y H:i', ) ?? null,

            email: Arr::get($data, 'email', null),
            fathername: Arr::get($data, 'fathername', null),
            firstname: Arr::get($data, 'firstname', null),
            lastname: Arr::get($data, 'lastname', null),
            person_type_id: Arr::get($data, 'person_type_id', null),
            phone: preg_replace("/[^,.0-9]/", '', Arr::get($data, 'phone', null)),
            time: Arr::get($data, 'time', null),
            trafic_chanel_id: Arr::get($data, 'trafic_chanel_id', null),
            trafic_interval: Arr::get($data, 'trafic_interval', null),
            manager_id: Arr::get($data, 'manager_id', null),
            trafic_sex_id: Arr::get($data, 'trafic_sex_id', null),
            trafic_zone_id: Arr::get($data, 'trafic_zone_id', null),
            trafic_need_id: Arr::get($data, 'trafic_need_id', null),

            trafic_brand_id: Arr::get($data, 'trafic_brand_id'),
            trafic_section_id: Arr::get($data, 'trafic_section_id'),
            trafic_appeal_id: Arr::get($data, 'trafic_appeal_id'),

            inn: Arr::get($data, 'inn'),
            client_type_id: Arr::get($data, 'person_type_id'),
            company_name: Arr::get($data, 'company_name'),
        );
    }
}