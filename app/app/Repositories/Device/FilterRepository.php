<?php

namespace App\Repositories\Device;

use App\Models\DeviceFilter;

Class FilterRepository
{
    public function delete(DeviceFilter $filter)
    {
        $name = $filter->name;
        $filter->delete();
        return response()->json([
            'message' => 'Фильтр по оборудованию '.$name.' удален',
            'status' => 1
        ]);
    }



    public function getAllSort()
    {
        $filters = DeviceFilter::orderBy('sort')->get();
        return $filters;
    }



    public function save(DeviceFilter $filter, $data = [])
    {
        $filter->fill($data);
        if(!$filter->id)
            $filter->sort = DeviceFilter::max('sort')+1;
        $filter->save();
        return $filter;
    }
}
