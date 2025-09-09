<?php

namespace App\Http\Resources\Color;

use Illuminate\Http\Resources\Json\JsonResource;

class ColorResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'web' => $this->web
        ];
    }
}
