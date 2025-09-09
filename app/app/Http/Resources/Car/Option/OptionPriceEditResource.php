<?php

namespace App\Http\Resources\Car\Option;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OptionPriceEditResource extends JsonResource
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
                'id'            => $this->id,
                'price'         => $this->price,
                'begin_at'      => $this->begin_at->format('d.m.Y'),
                'author'        => $this->author->cut_name,
                'created_at'    => $this->created_at->format('d.m.Y'),
            ],
            'success' => 1,
        ];
    }
}
