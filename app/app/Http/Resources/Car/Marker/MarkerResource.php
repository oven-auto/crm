<?php

namespace App\Http\Resources\Car\Marker;

use Illuminate\Http\Resources\Json\JsonResource;

class MarkerResource extends JsonResource
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
            'body_color' => $this->body_color,
            'text_color' => $this->text_color,
            'description' => $this->description,
            'trash' => (int)$this->trashed(),
            'update' => $this->updated_at ? $this->updated_at->format('d.m.Y (H:i)') : '',
            'author' => $this->author->cut_name,
        ];
    }
}
