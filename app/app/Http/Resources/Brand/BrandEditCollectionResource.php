<?php

namespace App\Http\Resources\Brand;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Brand\BrandEditResource;

class BrandEditCollectionResource extends ResourceCollection
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
            'message' => 'Найдено '.$this->collection->count().' бренда',
            'count' => $this->collection->count(),
        ];
    }
}
