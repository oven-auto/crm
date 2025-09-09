<?php

namespace App\Http\Controllers\Api\v1\Back\Worksheet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Worksheet\WorksheetAppendSubClientRequest;
use App\Http\Resources\Worksheet\WorksheetChangeClientResource;
use App\Services\Worksheet\WorksheetClient;
use App\Models\Worksheet;
use App\Models\Client;

class ChangeClientController extends Controller
{
    public function change(WorksheetAppendSubClientRequest $request)
    {
        $worksheet = Worksheet::findOrFail($request->worksheet_id);

        WorksheetClient::change($worksheet, Client::findOrFail($request->client_id));

        return new WorksheetChangeClientResource($worksheet);
    }
}
