<?php

namespace App\Http\Resources\Device;

use Illuminate\Http\Resources\Json\JsonResource;

class DeviceResource extends JsonResource
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
            'device_filter_id' => $this->device_filter_id,
            'device_type_id' => $this->device_type_id,
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
