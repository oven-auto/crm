<?php

namespace App\Http\Resources\Car;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarCountResource extends JsonResource
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
                'general' => [
                    'count'     => $this->count       ?? 0,
                    'base'      => $this->base        ?? 0,
                    'option'    => $this->option      ?? 0,
                    'over'      => $this->overprice   ?? 0,
                    'tuning'    => $this->tuning      ?? 0,
                    'gift'      => $this->giftprice   ?? 0,
                    'discount'  => $this->discount    ?? 0,
                    'reparation'=> $this->full_reparation ?? 0,
                    'full'      => array_sum([
                        $this->base, 
                        $this->option, 
                        $this->overprice, 
                        $this->tuning]
                    ) - $this->discount - $this->giftprice
                ],
                'report' => [
                    'count'     => $this->count       ?? 0,
                    'disable'   => $this->disable     ?? 0,
                    'owner'     => $this->owner       ?? 0,
                    'green'     => $this->green       ?? 0,
                    'yellow'    => $this->yellow      ?? 0,
                    'collector' => $this->collector   ?? 0,                    
                ],
                'factoring' => [
                    'count'         => $this->factoring_count,
                    'sum'           => $this->factoring_sum,
                    'detailing'     => $this->factoring_detailing,
                    'full'          => $this->factoring_sum + $this->factoring_detailing,
                    'reparation'    => $this->factoring_reparation,
                ],
                'ransom' => [
                    'count'         => $this->ransom_count,
                    'sum'           => $this->ransom_sum,
                    'detailing'     => $this->ransom_detailing,
                    'full'          => $this->ransom_sum + $this->ransom_detailing,
                    'reparation'    => $this->ransom_reparation,
                ]
            ],
            'success' => 1
        ];
    }
}
