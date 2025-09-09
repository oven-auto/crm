<?php

namespace App\Http\Resources\CountryFactory;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryFactoryEditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($this->id)
            return [
                'data' => [
                    'id' => $this->id,
                    'country' => $this->country,
                    'city' => $this->city,
                    'distributor' => $this->distributor,
                    'logistic' => $this->logistic
                ],
                'status' => 1,
                'message' => $this->isCreate() ? 'Производитель создан' : 'Производитель изменен'
            ];
        else
            return [
                'status' => 0,
                'message' => 'Такой Производитель не существует'
            ];
    }
}
