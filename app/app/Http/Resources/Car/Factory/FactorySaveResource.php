<?php

namespace App\Http\Resources\Car\Factory;

use Illuminate\Http\Resources\Json\JsonResource;

class FactorySaveResource extends JsonResource
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
                'id' => $this->id,
                'city' => $this->city,
                'country' => $this->country,
                'trash' => $this->trashed(),
            ],
            'success' => 1,
        ];
    }
}
