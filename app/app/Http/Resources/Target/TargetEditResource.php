<?php

namespace App\Http\Resources\Target;

use App\Http\Resources\Brand\BrandResource;
use App\Http\Resources\User\UserSmallResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TargetEditResource extends JsonResource
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
                'brand' => new BrandResource($this->brand),
                'date_at' => $this->date_at->format('m.Y'),
                'author' => new UserSmallResource($this->author),
                'amount' => $this->amount,
                'updated_at' => $this->updated_at->format('d.m.Y'),
                'marks' => $this->marks->map(function($item){
                    return [
                        'id' => $item->id,
                        'amount' => $item->pivot->amount,
                    ];
                })
            ],
            'success' => 1
        ];
    }
}
