<?php

namespace App\Http\Resources\Car\Option;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OptionPriceResource extends JsonResource
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
                'option' => $this['option'] ?? [],
                'prices' => $this['prices'] ?? [],
            ],
            'success' => 1,
        ];
    }
}
