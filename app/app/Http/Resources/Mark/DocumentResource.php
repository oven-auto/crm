<?php

namespace App\Http\Resources\Mark;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
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
            'brochure' => $this->url_brochure,
            'accessory' => $this->url_accessory,
            'manual' => $this->url_manual,
            'price' => $this->url_price,
        ];
    }
}
