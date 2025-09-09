<?php

namespace App\Http\Resources\Trafic;

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
            'text' => $this->text,
            'icon' => $this->icon,
            'author' => $this->author->cut_name,
            'trafic_id' => $this->trafic_id,
            'id' => $this->id,
            'created_at' => $this->created_at->format('d.m.Y (H:i)'),
            'type' => 'trafic',
        ];
    }
}
