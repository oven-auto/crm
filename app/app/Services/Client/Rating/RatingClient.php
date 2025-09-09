<?php

namespace App\Services\Client\Rating;

use App\Models\Client;

Class RatingClient
{
    
    public function getMostActive(int $limit = 10)
    {
        $clients = Client::query()
            ->select([
                'clients.*'
            ])
            ->groupBy('clients.id')
            ->get();

        return $clients;
    }
}