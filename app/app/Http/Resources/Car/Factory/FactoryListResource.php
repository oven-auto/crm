<?php

namespace App\Http\Resources\Car\Factory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FactoryListResource extends JsonResource
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
            'city' => $this->city,
            'country' => $this->country,
            'deleted_at' => $this->deleted_at ? 1 : 0,
            'trash' => $this->deleted_at ? 1 : 0,
        ];
    }
}
