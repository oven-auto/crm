<?php

namespace App\Http\Controllers\Api\v1\Back\Worksheet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Worksheet\WorksheetAppendSubClientRequest;
use App\Models\Client;
use App\Services\Worksheet\WorksheetClient;
use App\Models\Worksheet;

class AppendClientController extends Controller
{
    /**
     * ДОБАВИТЬ КОНТАКТНОЕ ЛИЦО В РЛ
     */
    public function append(WorksheetAppendSubClientRequest $request)
    {
        $client = Client::find($request->client_id);

        $worksheet = Worksheet::with('subclients')->find($request->worksheet_id);

        WorksheetClient::attach($worksheet, $client);

        return response()->json([
            'success' => 1,
            'client' => [
                'id' => $client->id,
                'name' => $client->full_name,
            ],
            'message' => 'Клиент добавлен',

        ]);
    }



    /**
     * УДАЛИТЬ КОНТАКТНОЕ ЛИЦО ИЗ РЛ
     */
    public function destroy(WorksheetAppendSubClientRequest $request)
    {
        $client = Client::find($request->client_id);

        $worksheet = Worksheet::with('subclients')->find($request->worksheet_id);

        WorksheetClient::detach($worksheet, $client);

        return response()->json([
            'success' => 1,
            'message' => 'Клиент удален'
        ]);
    }
}
