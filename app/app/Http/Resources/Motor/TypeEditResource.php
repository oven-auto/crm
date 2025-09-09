<?php

namespace App\Http\Resources\Motor;

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
        if($this->id)
            return [
                'data' => [
                    'id' => $this->id,
                    'name' => $this->name,
                    'acronym' => $this->acronym,
                ],
                'status' => 1,
                'message' => $this->isCreate() ? 'Тип мотора создан' : 'Тип мотора изменен'
            ];
        else
            return [
                'status' => 0,
                'message' => 'Такого типа мотора не существует'
            ];
    }
}
