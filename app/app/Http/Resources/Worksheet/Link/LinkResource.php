<?php

namespace App\Http\Resources\Worksheet\Link;

use Illuminate\Http\Resources\Json\JsonResource;

class LinkResource extends JsonResource
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
            'text' => $this->url,
            'icon' => $this->icon,
            'author' => $this->author->cut_name,
            'created_at' => $this->created_at->format('d.m.Y (H:i)'),
            'type' => 'worksheet'
        ];
    }
}
