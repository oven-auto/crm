<?php

namespace App\Http\Resources\Device\Image;

use Illuminate\Http\Resources\Json\JsonResource;

class ImageEditResource extends JsonResource
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
            'id' => $this->id,
            'device_id' => $this->device_id,
            'image' => $this->url_image
        ];
    }
}
