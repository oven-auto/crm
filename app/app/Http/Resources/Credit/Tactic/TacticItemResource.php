<?php

namespace App\Http\Resources\Credit\Tactic;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TacticItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => new TacticResource($this),
            'success' => 1
        ];
    }
}
