<?php

namespace App\Http\Resources\Target;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TargetListResource extends JsonResource
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
            'brand_id' => $this->brand_id,
            'amount' => $this->amount,
        ];
    }
}
