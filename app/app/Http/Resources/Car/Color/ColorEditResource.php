<?php

namespace App\Http\Resources\Car\Color;

use Illuminate\Http\Resources\Json\JsonResource;

class ColorEditResource extends JsonResource
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
                'base_id' => $this->base_id,
                'brand_id' => $this->brand_id,
                'mark_id' => $this->mark_id,
                'name' => $this->name,
                'editor' => $this->author->cut_name,
                'update' => $this->updated_at->format('d.m.Y (H:i)'),
                'trash' => (int)$this->trashed()
            ],
            'success' => 1
        ];
    }
}
