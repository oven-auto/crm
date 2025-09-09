<?php

namespace App\Http\Resources\Car\OrderType;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderTypeResource extends JsonResource
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
            'name' => $this->name,
            'text_color' => $this->text_color,
            'description' => $this->description,
            'trash' => (int)$this->trashed()
        ];
    }
}
