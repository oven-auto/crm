<?php

namespace App\Http\Resources\Car\Color;

use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'url' => $this->url,
            'id' => $this->id,
            'bodywork' => [
                'id' => $this->bodywork->id,
                'name' => $this->bodywork->name,
            ],
            'dealer_color_id' => $this->dealer_color_id,
        ];
    }
}
