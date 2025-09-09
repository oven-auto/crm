<?php

namespace App\Http\Resources\Worksheet\SubAction;

use Illuminate\Http\Resources\Json\JsonResource;

class SubActionUserResource extends JsonResource
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
            'name' => $this->cut_name
        ];
    }
}
