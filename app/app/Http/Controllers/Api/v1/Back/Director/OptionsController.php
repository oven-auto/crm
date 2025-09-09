<?php

namespace App\Http\Controllers\Api\v1\Back\Director;

use App\Http\Controllers\Controller;
use App\Models\TraficChanel;
use Illuminate\Http\Request;

class OptionsController extends Controller
{
    public function __invoke()
    {
        $filterOption['appeal_ids'] = auth()->user()->appeals->sortBy('sort')->map(fn($item) => [
            'name' => $item->name,
            'id' => $item->id
        ])->values()->toArray();

        $filterOption['structure_ids'] = auth()->user()->structures->map(fn($item) => [
            'name' => $item->structure->structure->name.' ('.$item->company->name.')',
            'id' => $item->company_structure_id,
            'company_id' => $item->company_id,
            'sort' => $item->company_id.$item->structure->structure->sort
        ])->sortBy('sort')->values()->toArray();

        $filterOption['company_ids'] = auth()->user()->companies->unique()->map(fn($item) => [
            'name' => $item->name,
            'id' => $item->id
        ]);

        $filterOption['chanels'] = TraficChanel::where(function($q){
            $q->where('parent', '<>', 0)
                ->orWhereNotIn('id', [39,7,6,5,4,3]);
        })->get()->map(function($item) {
            return [
                'name' => $item->name,
                'id' => $item->id,
            ];
        });

        return response()->json([
            'data' => $filterOption,
            'success' => 1,
        ]);
    }
}
