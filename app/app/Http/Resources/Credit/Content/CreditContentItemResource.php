<?php

namespace App\Http\Resources\Credit\Content;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreditContentItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => new CreditContentResource($this),
            'success' => 1
        ];
    }
}
