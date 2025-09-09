<?php

namespace App\Http\Resources\Color;

use Illuminate\Http\Resources\Json\JsonResource;

class ColorEditResource extends JsonResource
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
                    'code' => $this->code,
                    'web' => $this->web,
                    'brand_id' => $this->brand_id
                ],
                'status' => 1,
                'message' => $this->isCreate() ? 'Цвет создан' : 'Цвет изменен'
            ];
        else
            return [
                'status' => 0,
                'message' => 'Такой цвет не существует'
            ];
    }
}
