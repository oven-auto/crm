<?php

namespace App\Http\Resources\Worksheet\Credit;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreditItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => new CreditResource($this),
            'success' => 1
        ];
    }
}
