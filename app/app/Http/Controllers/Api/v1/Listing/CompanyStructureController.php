<?php

namespace App\Http\Controllers\Api\v1\Listing;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyStructure;
use Illuminate\Http\Request;

class CompanyStructureController extends Controller
{
    public function __invoke()
    {
        $companies = Company::get();
        $structures = CompanyStructure::orderBy('structure_id')->get();

        $arr = [];

        foreach($companies as $company)
        {
            $item = [
                'id' => $company->id,
                'name' => $company->name
            ];

            $arr[] = [
                'company' => $item,
                'structures' => $structures->filter(function($item) use ($company) {
                        if ($item->company_id == $company->id)
                            return $item;
                    })->map(function($item){
                        return [
                            'id' => $item->id,
                            'name' => $item->structure->name
                        ];
                    })->values()->toArray(),
            ];
        }

        return response()->json([
            'data' => $arr,
            'success' => 1,
        ]);
    }
}
