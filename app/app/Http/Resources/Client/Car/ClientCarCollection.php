<?php

namespace App\Http\Resources\Client\Car;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ClientCarCollection extends ResourceCollection
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
            'success' => 1,
        ];
    }
}
