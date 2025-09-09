<?php

namespace App\Http\Resources\Motor;

use Illuminate\Http\Resources\Json\JsonResource;

class TransmissionListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'acronym' => $this->acronym,
            'transmission_type_id' => $this->transmission_type_id
        ];
    }
}
