<?php

namespace App\Http\Controllers\Api\v1\Back\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\ClientExport;
use Maatwebsite\Excel\Excel;

class ClientExportController extends Controller
{
    public function __invoke(Request $request, \App\Repositories\Client\ClientRepository $service)
    {
        $data = $service->export($request->all());

        $export = ClientExport::setData($data);

        return Excel::download($export, 'clients.xlsx');
    }
}
