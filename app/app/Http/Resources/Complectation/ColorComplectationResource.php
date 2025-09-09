<?php

namespace App\Http\Resources\Complectation;

use Illuminate\Http\Resources\Json\JsonResource;

class ColorComplectationResource extends JsonResource
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

            'color' => $this->color,

            'color_id' => $this->color_id,
            'id' => $this->id,
            'image' => $this->image_date,

            'mark_id' => $this->mark_id,
            'color_pack' => $this->installColorPack ? $this->installColorPack : []

        ];
    }
}
