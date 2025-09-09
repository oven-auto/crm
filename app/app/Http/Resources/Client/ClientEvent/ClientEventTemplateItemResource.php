<?php

namespace App\Http\Resources\Client\ClientEvent;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientEventTemplateItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => new ClientEventTemplateResource($this),
            'success' => 1,
        ];
    }
}
