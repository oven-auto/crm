<?php

namespace App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Reserve;

use App\Exports\CarExport;
use App\Http\Controllers\Controller;
use App\Repositories\Worksheet\Modules\Reserve\ReserveRepository;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReserveExcelController extends Controller
{
    private $repo;

    public function __construct(ReserveRepository $repo)
    {
        $this->repo = $repo;
    }



    public function index(Request $request)
    {
        $reserves = $this->repo->get($request->all());

        $cars = $reserves->map(function($item){
            return $item->car;
        });

        $export = (new CarExport($cars));

        return Excel::download($export, 'cars.xlsx');
    }
}
