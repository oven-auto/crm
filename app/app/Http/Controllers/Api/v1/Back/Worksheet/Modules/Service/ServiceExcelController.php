<?php

namespace App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Service;

use App\Exports\ServiceExport;
use App\Http\Controllers\Controller;
use App\Repositories\Worksheet\Modules\Service\ServiceWorksheetRepository;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ServiceExcelController extends Controller
{
    public function __construct(
        private ServiceWorksheetRepository $repo
    )
    {
        
    }



    public function index(Request $request)
    {
        $services = $this->repo->get($request->all());
        
        $export = (new ServiceExport($services));

        return Excel::download($export, 'cars.xlsx');
    }
}
