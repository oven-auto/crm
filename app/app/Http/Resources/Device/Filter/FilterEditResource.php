<?php

namespace App\Http\Resources\Device\Filter;

use Illuminate\Http\Resources\Json\JsonResource;

class FilterEditResource extends JsonResource
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
            'message' => $this->isCreate() ? 'Фильтр по оборудованию '.$this->name.' создан' : 'Фильтр по оборудованию '.$this->name.' изменен'
        ];
    }
}
