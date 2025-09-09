<?php

namespace App\Http\Resources\Brand;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
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
            'icon' => $this->icon_date,
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'brand_color' => $this->brand_color,
            'font_color' => $this->font_color
        ];
    }
}
