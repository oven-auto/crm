<?php

namespace App\Http\Resources\Motor;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverEditResource extends JsonResource
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
                    'driver_type_id' => $this->driver_type_id
                ],
                'status' => 1,
                'message' => $this->isCreate() ? 'Тип привода создан' : 'Тип привода изменен'
            ];
        else
            return [
                'status' => 0,
                'message' => 'Такого типа привода не существует'
            ];
    }
}
