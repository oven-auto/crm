<?php

namespace App\Http\Resources\Car\Collector;

use Illuminate\Http\Resources\Json\JsonResource;

class CollectorSaveResource extends JsonResource
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
                'trash' => (int) $this->trashed(),
                'created_at' => $this->created_at->format('d.m.Y'),
                'deleted_at' => $this->deleted_at ? $this->deleted_at->format('d.m.Y') : null,
            ],
            'success' => 1,
        ];
    }
}
