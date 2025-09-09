<?php

namespace App\Repositories\Worksheet\DTO;

use App\Classes\DTO\AbstractDTO;
use App\Models\Client;
use App\Models\Trafic;

Class WorksheetCreateDTO extends AbstractDTO
{
    public function __construct(Trafic $trafic, Client $client)
    {
        $this->data = [
            'client_id'         => $client->id,
            'trafic_id'         => $trafic->id,
            'company_id'        => $trafic->salon->id,
            'structure_id'      => $trafic->structure->id,
            'appeal_id'         => $trafic->appeal->id,
            'author_id'         => auth()->user()->id, 
            'status_id'         => 'work',
        ];
    }
}