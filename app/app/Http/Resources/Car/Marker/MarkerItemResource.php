<?php

namespace App\Http\Resources\Car\Marker;

use Illuminate\Http\Resources\Json\JsonResource;

class MarkerItemResource extends JsonResource
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
                'body_color' => $this->body_color,
                'text_color' => $this->text_color,
                'description' => $this->description,
                'author' => $this->author->cut_name,
                'update' => $this->updated_at->format('d.m.Y (H:i)'),
                'trash' => (int)$this->trashed()
            ],
            'success' => 1,
        ];
    }
}
