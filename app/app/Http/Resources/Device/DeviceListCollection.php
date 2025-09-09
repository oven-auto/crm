<?php

namespace App\Http\Resources\Device;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Device\DeviceEditResource;

class DeviceListCollection extends ResourceCollection
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
            'message' => 'Найдено '.$this->collection->count().' едениц оборудования',
            'count' => $this->collection->count()
        ];
    }
}
