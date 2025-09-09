<?php

namespace App\Http\Resources\Device\Filter;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FilterListCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'status' => $this->collection->count() ? 1 : 0,
            'message' => 'Найдено '.$this->collection->count().' едениц фильтров по оборудованию',
            'count' => $this->collection->count()
        ];
    }
}
