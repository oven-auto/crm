<?php

namespace App\Http\Resources\Car\Collector;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CollectorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'trash' => (int) $this->trashed(),
            'created_at' => $this->created_at->format('d.m.Y'),
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('d.m.Y') : null,
        ];
    }
}
