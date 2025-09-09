<?php

namespace App\Http\Resources\Mark;

use Illuminate\Http\Resources\Json\JsonResource;

class ColorImageResource extends JsonResource
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
            'id' => $this->color_id,
            'name' => $this->color->name,
            'code' => $this->color->code,
            'web' => $this->color->web,
            'img' => $this->image_date,
        ];
    }
}
