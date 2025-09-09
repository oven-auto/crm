<?php

namespace App\Http\Resources\BodyWork;

use Illuminate\Http\Resources\Json\JsonResource;

class BodyWorkEditResource extends JsonResource
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
                    'sort' => $this->sort
                ],
                'status' => 1,
                'message' => $this->isCreate() ? 'Тип кузова создан' : 'Тип кузова изменен'
            ];
        else
            return [
                'status' => 0,
                'message' => 'Такого типа кузова не существует'
            ];
    }
}
