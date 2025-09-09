<?php

namespace App\Repositories\Property;

use App\Models\Property;

Class PropertyRepository
{
    public function getAll($data = [])
    {
        $query = Property::query();
        foreach($data as $key => $item)
            if($key == 'sort')
                $query->orderBy($item);
        $properties = $query->get();
        return $properties;
    }

    public function save(Property $property, $data = [])
    {
        $property->fill($data);
        if(!$property->id)
            $property->sort = Property::max('sort')+1;
        $property->save();
        return $property;
    }
}
