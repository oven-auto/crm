<?php

namespace App\Http\Resources\Car\Option;

use Illuminate\Http\Resources\Json\JsonResource;

class OptionItemResource extends JsonResource
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
            'data' => [
                'id' => $this->id,
                'name' => $this->name,
                'code' => $this->code,
                'price' => $this->price,
                'mark_id' => $this->mark_id,
                'brand_id' => $this->brand_id,
                'author' => $this->author->cut_name,
                'update' => $this->updated_at->format('d.m.Y (H:i)'),
                'trash' => (int)$this->trashed()
            ],
            'success' => 1,
        ];
    }
}
