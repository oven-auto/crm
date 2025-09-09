<?php

namespace App\Http\Resources\Motor;

use Illuminate\Http\Resources\Json\JsonResource;

class TransmissionEditResource extends JsonResource
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
                    'name' => $this->name,
                    'acronym' => $this->acronym,
                    'transmission_type_id' => $this->transmission_type_id
                ],
                'status' => 1,
                'message' => $this->isCreate() ? 'Тип трансмиссии создан' : 'Тип трансмиссии изменен'
            ];
        else
            return [
                'status' => 0,
                'message' => 'Такого типа трансмиссии не существует'
            ];
    }
}
