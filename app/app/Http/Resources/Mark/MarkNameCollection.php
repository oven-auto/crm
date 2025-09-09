<?php

namespace App\Http\Resources\Mark;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MarkNameCollection extends ResourceCollection
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
            'status' => $this->collection->count()
        ];
    }
}
