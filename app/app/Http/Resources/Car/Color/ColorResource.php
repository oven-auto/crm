<?php

namespace App\Http\Resources\Car\Color;

use Illuminate\Http\Resources\Json\JsonResource;

class ColorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'base' => $this->base->name,
            'brand' => $this->brand->name,
            'mark' => $this->mark->name,
            'name' => $this->name,
            'trash' => (int)$this->trashed()
        ];
    }
}
