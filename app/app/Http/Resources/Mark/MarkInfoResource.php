<?php

namespace App\Http\Resources\Mark;

use Illuminate\Http\Resources\Json\JsonResource;

class MarkInfoResource extends JsonResource
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
            'description' => $this->description,
            'id' => $this->id,
            'mark_id' => $this->mark_id,
            'slogan' => $this->slogan
        ];
    }
}
