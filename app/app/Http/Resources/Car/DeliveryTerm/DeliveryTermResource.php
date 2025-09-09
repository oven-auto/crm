<?php

namespace App\Http\Resources\Car\DeliveryTerm;

use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryTermResource extends JsonResource
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
            'author' => $this->author->cut_name,
            'update' => $this->updated_at->format('d.m.Y (H:i)'),
            'trash' => (int)$this->trashed(),
            'begin_period' => $this->begin_period,
            'end_period' => $this->end_period,
        ];
    }
}
