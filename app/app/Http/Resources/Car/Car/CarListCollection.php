<?php

namespace App\Http\Resources\Car\Car;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CarListCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            // 'count' => $this->collection->count(),
            // 'ids' => $this->collection->map(fn($item) => $item->id),
            'data' => $this->collection,
            'success' => 1,
        ];
    }
}
