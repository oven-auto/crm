<?php

namespace App\Http\Controllers\Api\v1\Back\Trafic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Http\Resources\Trafic\TraficSexCollection;
use App\Models\CompanyStructure;

class TraficStructureController extends Controller
{
    public function index($brand_id)
    {
        $structures = CompanyStructure::select('company_structures.*')->with(['structure'])
            ->leftJoin('structures','structures.id','company_structures.structure_id')
            ->where('company_structures.company_id', $brand_id)
            ->orderBy('structures.sort')
            ->get();

        $data = $structures->map(function($item){
            return (object) [
                'id'=>$item->id,
                'name'=>$item->structure->name,
            ];
        });
        
        return new TraficSexCollection($data);
    }
}
