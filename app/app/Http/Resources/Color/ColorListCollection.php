<?php

namespace App\Http\Resources\Color;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ColorListCollection extends ResourceCollection
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
            'message' => 'Найдено '.$this->collection->count().' цветов',
            'count' => $this->collection->count()
        ];
    }
}
