<?php

namespace App\Http\Resources\Pack;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Device\DeviceResource;

class PackListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'brand_id' => $this->brand_id,
            'code' => $this->code,
            'colored' => $this->colored,
            'complectation_id' => $this->complectation_id,
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,

            'devices' => DeviceResource::collection($this->devices),
            'marks' => $this->marks,
            'brand' => $this->brand
        ];
    }
}
