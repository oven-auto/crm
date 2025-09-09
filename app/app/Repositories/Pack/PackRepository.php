<?php

namespace App\Repositories\Pack;

use App\Models\Pack;
use App\Http\Filters\PackFilter;
use Illuminate\Support\Arr;

Class PackRepository
{
	public function getByCarId($car_id)
	{
		$packs = Pack::select('packs.*')
			->ByCarId($car_id)
			->with('devices')
			->get();

		return $packs;
	}

	public function getByComplectationId($complectation_id)
	{
		$packs = Pack::select('packs.*')
			->ByComplectId($complectation_id)
			->with('devices')
			->get();

		return $packs;
    }

    public function filter($data = [])
    {
        $query = Pack::fullData()->with('marks');
        $filter = app()->make(PackFilter::class, ['queryParams' => array_filter($data)]);
        $packs = $query->filter($filter)->get();
        return $packs;
    }

    public function save(Pack $pack, $data = []) :Pack
    {
        $pack->fill(Arr::except($data, ['devices','marks']))->save();
        $pack->devices()->sync($data['devices']);
        $pack->marks()->sync($data['marks']);
        return $pack;
    }

    public function destroy($pack)
    {
        $name = $pack->code;
        $pack->delete();
        return response()->json([
            'data' => '',
            'status' => 1,
            'message' => 'Пакет с кодом '.$name.' удален'
        ]);
    }
}
