<?php

namespace App\Http\Resources\Bodywork;

use Illuminate\Http\Resources\Json\JsonResource;

class BodyworkSaveResource extends JsonResource
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
            'vehicle' => $this->vehicle->id,
            'acronym' => $this->acronym,
        ];
    }
}
