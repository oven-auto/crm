<?php

namespace App\Repositories\Car\DetailingCost;

use App\Models\DetailingCost;
use Illuminate\Database\Eloquent\Collection;

Class DetailingCostRepository
{
    public function get(array $data) : Collection
    {
        $query = DetailingCost::query()->with('author');

        if(isset($data['trash']))
            $query->onlyTrashed();

        $costs = $query->get();
        
        return $costs;
    }



    public function save(DetailingCost $cost, array $data) : void
    {
        if(!$cost->id)
            $data['author_id'] = auth()->user()->id;

        $cost->fill($data)->save();
    }



    public function delete(DetailingCost $cost) : void
    {
        $cost->delete();
    }



    public function restore(DetailingCost $cost) : void
    {
        $cost->restore();
    }
}