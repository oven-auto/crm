<?php

namespace App\Http\Resources\Mark;

use Illuminate\Http\Resources\Json\JsonResource;

class MarkBaseComplectationResource extends JsonResource
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
            'price' => $this->price
        ];
    }
}
