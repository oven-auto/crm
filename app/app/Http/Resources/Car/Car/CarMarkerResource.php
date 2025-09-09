<?php

namespace App\Http\Resources\Car\Car;

use Illuminate\Http\Resources\Json\JsonResource;

class CarMarkerResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'text_color' => $this->text_color,
            'body_color' => $this->body_color,
        ];
    }
}
