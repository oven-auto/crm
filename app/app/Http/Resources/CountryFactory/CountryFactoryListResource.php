<?php

namespace App\Http\Resources\CountryFactory;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryFactoryListResource extends JsonResource
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
            'country' => $this->country,
            'city' => $this->city,
            'distributor' => $this->distributor,
            'logistic' => $this->logistic
        ];
    }
}
