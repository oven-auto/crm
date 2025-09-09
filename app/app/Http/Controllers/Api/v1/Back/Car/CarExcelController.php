<?php

namespace App\Http\Controllers\Api\v1\Back\Car;

use App\Exports\CarExport;
use App\Http\Controllers\Controller;
use App\Repositories\Car\Car\CarRepository;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CarExcelController extends Controller
{
    private $repo;

    public function __construct(CarRepository $repo)
    {
        $this->repo = $repo;    
    }



    /**
     * @OA\Get(
     *      path="/export/cars",
     *      operationId="exportCars",
     *      tags={"Новый автомобиль", "Экспорт"},
     *      summary="Экспорт новых автомобилей Excel",
     *      description="Экспорт новых автомобилейв Excel",
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/CarFilter",
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function index(Request $request)
    {
        $cars = $this->repo->get($request->all());
        
        $export = (new CarExport($cars));

        return Excel::download($export, 'cars.xlsx');
    }
}
