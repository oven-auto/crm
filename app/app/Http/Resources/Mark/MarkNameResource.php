<?php

namespace App\Http\Resources\Mark;

use Illuminate\Http\Resources\Json\JsonResource;

class MarkNameResource extends JsonResource
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
            'name' => $this->name,
            'prefix' => $this->prefix,
            'id' => $this->id, 
            'slug' => $this->slug
        ];
    }
}
