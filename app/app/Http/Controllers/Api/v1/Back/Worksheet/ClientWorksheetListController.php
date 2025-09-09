<?php

namespace App\Http\Controllers\Api\v1\Back\Worksheet;

use App\Http\Controllers\Controller;
use App\Models\Worksheet;
use Illuminate\Http\Request;

class ClientWorksheetListController extends Controller
{
    public function __invoke($client)
    {
        $data = Worksheet::where('client_id', $client)->with('last_action')->orderBy('id', 'DESC')->get();

        return new \App\Http\Resources\Client\WorksheetListCollection($data);
    }
}
