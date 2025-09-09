<?php

namespace App\Http\Resources\ServiceProduct;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceProductSaveResource extends JsonResource
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
                'description' => $this->description,
                'price' => $this->price,
                'duration' => $this->duration,
                'group_id' => $this->group_id,
                'appeals' => $this->appeals->map(function($item){
                    return ['id' => $item->id, 'name' => $item->name];
                })
            ],
            'success' => 1
        ];
    }
}
