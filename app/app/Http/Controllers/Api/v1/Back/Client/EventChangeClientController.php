<?php

namespace App\Http\Controllers\Api\v1\Back\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientEvent;
use App\Models\ClientEventStatus;
use App\Services\Client\EventChangeClient;
use Illuminate\Http\Request;

class EventChangeClientController extends Controller
{
    /**
     * @param Request $request - [event_status_id, client_id]
     */
    public function client(Request $request)
    {
        $client = EventChangeClient::changeClient($request->event_status_id, $request->client_id);

        return response()->json([
            'name' => $client->full_name,
            'success' => 1,
            'message' => 'Клиент изменен',
        ]);
    }

    public function author(Request $request)
    {
        $user = EventChangeClient::changeAuthor($request->event_status_id, $request->author_id);

        return response()->json([
            'name' => $user->cut_name,
            'success' => 1,
            'message' => 'Автор изменен',
        ]);
    }
}
