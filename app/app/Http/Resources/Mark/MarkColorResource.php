<?php

namespace App\Http\Resources\Mark;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Color\ColorResource;

class MarkColorResource extends JsonResource
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
            'color_id' => $this->color_id,
            'mark_id' => $this->mark_id,
            'image' => $this->image_date,
            'color' => new ColorResource($this->color)
        ];
    }
}
