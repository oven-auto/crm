<?php

namespace App\Http\Resources\Client\Event\Link;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientEventLinkResource extends JsonResource
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
            'id'            => $this->id,
            'text'          => $this->url,
            'author'        => $this->author->cut_name,
            'created_at'    => $this->created_at->format('d.m.Y (H:i)'),
            'icon'          => $this->icon,
        ];
    }
}
