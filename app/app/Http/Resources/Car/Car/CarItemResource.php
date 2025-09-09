<?php

namespace App\Http\Resources\Car\Car;

use Illuminate\Http\Resources\Json\JsonResource;

class CarItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => [
                'main' => new CarMainResource($this),
                'support' => new CarSupportResource($this),
            ],
            'success' => 1,
        ];
    }
}
