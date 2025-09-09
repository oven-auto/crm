<?php

namespace App\Http\Resources\ServiceProduct;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceProductResource extends JsonResource
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
            'description' => $this->description,
            'price' => $this->price !== null ? $this->price : '',
            'duration' => $this->duration,
            'group' => $this->group->name,
            'appeals' => $this->appeals->map(function($item){
                return ['id' => $item->id, 'name' => $item->name];
            })
        ];
    }
}
