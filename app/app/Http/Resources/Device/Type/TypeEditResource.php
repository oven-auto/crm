<?php

namespace App\Http\Resources\Device\Type;

use Illuminate\Http\Resources\Json\JsonResource;

class TypeEditResource extends JsonResource
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
            'data' => [
                'id' => $this->id,
                'name' => $this->name,
                'sort' => $this->sort
            ],
            'status' => 1,
            'message' => $this->isCreate() ? 'Категория оборудования '.$this->name.' создана' : 'Категория оборудования '.$this->name.' изменена'
        ];
    }
}
