<?php

namespace App\Http\Resources\Car\Complectation;

use App\Helpers\String\StringHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class PriceComplectationResource extends JsonResource
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
            'id' => $this->id,
            'complectation_id' => $this->complectation_id,
            'price' => $this->price,
            'begin_at' => $this->begin_at,
            'author' => $this->author,
            'current' => $this->current,
            'created_at' => $this->created_at
        ];
    }
}
