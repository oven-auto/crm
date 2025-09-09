<?php

namespace App\Http\Resources\ServiceProduct;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupSaveResource extends JsonResource
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
            'success' => 1
        ];
    }
}
