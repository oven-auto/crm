<?php

namespace App\Services\Client;
use App\Models\Client;
use App\Models\Trafic;
use App\Services\Worksheet\WorksheetCheck;

Class CheckClient
{
    public static function check($data)
    {
        $worksheets = null;
        $trafic = null;

        $query = Client::select('clients.*');

        if(isset($data['phone']))
            $query->leftJoin('client_phones','client_phones.client_id','clients.id')
                ->where('client_phones.phone', $data['phone']);

        if(isset($data['inn']))
            $query->leftJoin('client_inns','client_inns.client_id','clients.id')
                ->where('client_inns.number', $data['inn']);

        $client = $query->first();

        if($client)
        {
            $trafic = Trafic::find($data['trafic_id']);
            $worksheets = WorksheetCheck::check($client, $trafic);
        }

        return [
            'client' => self::formatedClient($client, $data),
            'worksheets' => $worksheets,
        ];
    }

    public static function formatedClient($client, $data)
    {
        if($client)
            return [
                'type' => $client->type->name,
                'name' => $client->full_name,
                'id' => $client->id,
                'attribute' => isset($data['phone']) ? \StrHelp::phoneMask($data['phone']) : $data['inn'],
                'last_worksheet' => [
                    'id' => $client->latest_worksheet->id,
                    'created_at' => $client->latest_worksheet->created_date,
                    'salon' => $client->latest_worksheet->company->name,
                    'structure' => $client->latest_worksheet->structure->name,
                    'appeal' => $client->latest_worksheet->appeal->name,
                ],
            ];
        return null;
    }
}

