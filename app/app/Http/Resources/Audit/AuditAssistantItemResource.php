<?php

namespace App\Http\Resources\Audit;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditAssistantItemResource extends JsonResource
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
                'id' => $this->id,
            ],
            'success' => 1,
        ];
    }
}
