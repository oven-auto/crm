<?php

namespace App\Services\Complectation;

use App\Models\Complectation;

Class ComplectationService
{
	public function getAll($data)
	{
		$query = Complectation::with(['motor']);

    	if(isset($data['mark_id']))
    		$query->withCount('cars')->where('mark_id', $data['mark_id']);

    	$complectations = $query->get();

    	return $complectations;
	}

	public function getById($id, $data =[])
	{
		$query = Complectation::with('motor');
        
        if(isset($data['brand']))
            $query->with('brand');
        if(isset($data['mark']))
            $query->with('mark');

    	$complectation = $query->find($id);

    	return $complectation;
	}

    public function getComplectationImages($complect_id)
    {
        $complectation = Complectation::with(['colors','colorPacks'])->find($complect_id);
        foreach ($complectation->colors as $key => $item) {
            $item->image = asset('storage/' . $item->image . '?' . date('dmyh'));
            $mas = [];
            foreach($complectation->colorPacks as $pack) {
                if($item->color_id == $pack->color_id)
                    $mas[] = $pack->pivot->pack_id;
            }
            $item->color_packs = $mas;
        }

        return $complectation;
    }
}