<?php

namespace App\Http\Resources\Car\Owner;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreOwnerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => [
                'owner' => [
                    'name' => $this->client->full_name,
                    'phone' => $this->client->phones->first()->phone,
                ],
                'author' => $this->author->cut_name,
                'created_at' => $this->created_at->format('d.m.Y (H:i)'),
            ],
            'success' => 1
        ];
    }
}
