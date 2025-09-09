<?php

namespace App\Http\Resources\Worksheet\SubAction;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SubActionListCollection extends ResourceCollection
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
            'data' => $this->collection,
            'success' => 1,
        ];
    }
}
