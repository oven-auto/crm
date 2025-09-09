<?php

namespace App\Http\Resources\Service;

use App\Http\Resources\User\UserSmallResource;
use App\Http\Resources\Worksheet\Reserve\ReserveList\ClientResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => new ServiceResource($this),
            'success' => 1
        ];
    }
}
