<?php

namespace App\Http\Resources\Car\DetailingCost;

use Illuminate\Http\Resources\Json\JsonResource;

class DetailingCostItemResource extends JsonResource
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
                'name' => $this->name,
                'trash' => (int)$this->trashed()
            ],
            'success' => 1
        ];
    }
}
