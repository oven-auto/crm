<?php

namespace App\Http\Resources\BodyWork;

use Illuminate\Http\Resources\Json\JsonResource;

class BodyWorkListResource extends JsonResource
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
            'sort' => $this->sort
        ];
    }
}
