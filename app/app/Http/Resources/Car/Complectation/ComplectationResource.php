<?php

namespace App\Http\Resources\Car\Complectation;

use Illuminate\Http\Resources\Json\JsonResource;

class ComplectationResource extends JsonResource
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
            'name' => $this->name,
            'code' => $this->code,
            'factory' => $this->factory->fullName,
            'brand' => $this->mark->brand->name,
            'mark' => $this->mark->name,
            'vehicle' => $this->vehicle->name,
            'size' => $this->motor->size,
            'power' => $this->motor->power,
            'driver' => $this->motor->driver->acronym,
            'transmission' => $this->motor->transmission->acronym,
            'trash' => (int)$this->trashed(),
            'body' => $this->bodywork->name,
            'price' => $this->current_price->price ? [
                'price' => $this->current_price->price,
                'begin_at' => $this->current_price->begin_at->format('d.m.Y')
            ] : '',
        ];
    }
}
