<?php

namespace App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Credit;

use App\Exports\CreditExport;
use App\Http\Controllers\Controller;
use App\Repositories\Worksheet\Modules\Credit\CreditRepository;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CreditExcelController extends Controller
{
    public function __construct(
        private CreditRepository $repo
    )
    {
        
    }



    public function index(Request $request)
    {
        $services = $this->repo->get($request->all());
        
        $export = (new CreditExport($services));

        return Excel::download($export, 'cars.xlsx');
    }
}
