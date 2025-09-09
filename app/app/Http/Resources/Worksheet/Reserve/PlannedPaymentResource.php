<?php

namespace App\Http\Resources\Worksheet\Reserve;

use App\Http\Resources\User\UserSmallResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlannedPaymentResource extends JsonResource
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
                'date_at' => $this->date_at->format('d.m.Y'),
                'reserve_id' => $this->reserve_id,
                'author' => new UserSmallResource($this->author),
                'updated_at' => $this->updated_at->format('d.m.Y'),
                'dealtype' => [
                    'id' => $this->deal_type->id,
                    'name' => $this->deal_type->name,
                ]
            ],
            'success' => 1
        ];
    }
}
