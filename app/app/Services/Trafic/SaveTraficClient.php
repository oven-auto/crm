<?php

namespace App\Services\Trafic;

use App\Models\Trafic;

Class SaveTraficClient extends AbstractTraficSaveService
{
    public function action(Trafic $trafic, array $data)
    {
        $arr = [
            'firstname' => $data['firstname'] ?? '',
            'lastname'  => $data['lastname'] ?? '',
            'fathername' => $data['fathername'] ?? '',
            'inn'       => $data['inn'] ?? null,
            'company_name' => $data['company_name'] ?? null,
            'phone' => isset($data['phone']) ? preg_replace("/[^,.0-9]/", '', $data['phone']) : '',
            'email' => $data['email'] ?? null,
            'trafic_sex_id' => $data['trafic_sex_id'] ?? NULL,
            'client_type_id' => isset($data['person_type_id']) ? $data['person_type_id'] : ($arr['client_type_id'] ?? null),
            'empty_phone' => isset($data['empty_phone']) && $data['empty_phone'] ? 1 : 0,
        ];
        
        $count = 0;

        foreach($arr as $item)
            if($item != '')
            {
                $count = 1;
                break;
            }

        if($count)
            $trafic->client->fill($arr)->save();
    }
}