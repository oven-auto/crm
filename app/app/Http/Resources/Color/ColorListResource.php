<?php

namespace App\Http\Resources\Color;

use Illuminate\Http\Resources\Json\JsonResource;

class ColorListResource extends JsonResource
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
            'name' => $this->name,
            'code' => $this->code,
            'web' => $this->web,
            'brand_id' => $this->brand_id,
            'brand' => $this->brand
        ];
    }
}
