<?php

namespace App\Http\Resources\Property;

use Illuminate\Http\Resources\Json\JsonResource;

class PropertyEditResource extends JsonResource
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
                ],
                'status' => 1,
                'message' => $this->isCreate() ? 'Характеристика создана' : 'Характеристика изменена'
            ];
        else
            return [
                'status' => 0,
                'message' => 'Такой характеристики не существует'
            ];
    }
}
