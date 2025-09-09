<?php

namespace App\Http\Resources\Client;

use App\Helpers\String\StringHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class UnionResource extends JsonResource
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
            'name' => $this->full_name,
            'status' => '',
            'attribute' => $this->isCompany() ? [$this->inn->number] : $this->phones->map(function($item){
                return StringHelper::phoneMask($item->phone);
            }),
        ];
    }
}
