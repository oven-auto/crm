<?php

namespace App\Http\Resources\Credit\Tactic;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TacticResource extends JsonResource
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
            'description' => $this->description ?? '',
            'trash' => $this->deleted_at ? 1 : 0,
        ];
    }
}
