<?php

namespace App\Repositories\Device;

use App\Models\DeviceType;

Class TypeRepository
{
    public function delete(DeviceType $type)
    {
        $name = $type->name;
        $type->delete();
        return response()->json([
            'message' => 'Категория оборудования '.$name.' удалено',
            'status' => 1
        ]);
    }



    public function getAllSort()
    {
        $types = DeviceType::orderBy('sort')->get();
        return $types;
    }



    public function save(DeviceType $type, $data = [])
    {
        $type->fill($data);
        if(!$type->id)
            $type->sort = DeviceType::max('sort')+1;
        $type->save();
        return $type;
    }
}
