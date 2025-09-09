<?php

namespace App\Http\Resources\Trafic\TraficFile;

use Illuminate\Http\Resources\Json\JsonResource;

class TraficFileResource extends JsonResource
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
            'file' => $this->getFile('filepath'),
            'author' => $this->user->cut_name,
            'created_at' => $this->created_at->format('d.m.Y (H:i)'),
        ];
    }
}
