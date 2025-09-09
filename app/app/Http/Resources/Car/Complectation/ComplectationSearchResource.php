<?php

namespace App\Http\Resources\Car\Complectation;

use Illuminate\Http\Resources\Json\JsonResource;

class ComplectationSearchResource extends JsonResource
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
                    'code' => $this->code,
                    'factory' => $this->factory->fullName,
                    'brand' => $this->mark->brand->name,
                    'mark' => $this->mark->name,
                    'vehicle' => $this->vehicle->name,
                    'bodywork' => $this->bodywork->name,
                    'size' => $this->motor->size,
                    'power' => $this->motor->power,
                    'driver' => [
                        'acronym' => $this->motor->driver->acronym,
                        'name' => $this->motor->driver->name,
                    ],
                    'transmission' => [
                        'acronym' => $this->motor->transmission->acronym,
                        'name' => $this->motor->transmission->name,
                    ],
                    'type' => $this->motor->type->name,
                    'price' => $this->price,
                ],
                'success' => 1,
            ];
    }
}
