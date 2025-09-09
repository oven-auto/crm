<?php

namespace App\Http\Resources\Car\Car;

use Illuminate\Http\Resources\Json\JsonResource;

class CarComplectationResource extends JsonResource
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
            'name' => $this->complectation->name,
            'complectation_code'        => $this->complectation->code,
            'factory' => $this->complectation->factory->fullName,
            'vehicle' => $this->complectation->vehicle->name,
            'size' => $this->complectation->motor->size,
            'power' => $this->complectation->motor->power,
            'driver' => [
                'name' => $this->complectation->motor->driver->name,
                'acronym' => $this->complectation->motor->driver->acronym,
            ],
            'transmission' => [
                'name' => $this->complectation->motor->transmission->name,
                'acronym' => $this->complectation->motor->transmission->acronym,
            ],
            'type' => $this->complectation->motor->type->name,
            'bodywork' => $this->complectation->bodywork->name,
            'price' => $this->complectation->price,
            'file' => $this->complectation->getUrlFile(),
            'trash' => $this->complectation->deleted_at ? 1 : 0,
        ];
    }
}
