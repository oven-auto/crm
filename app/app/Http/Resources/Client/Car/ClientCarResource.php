<?php

namespace App\Http\Resources\Client\Car;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientCarResource extends JsonResource
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
            'brand' => $this->brand->name,
            'mark' => $this->mark->name,
            'bodywork' => $this->bodywork->name,
            'vin' => $this->vin ? $this->vin : '',
            'odometer' => $this->odometer ? $this->odometer : '',
            'register_plate' => $this->register_plate ? $this->register_plate : '',
            'year' => $this->year ? $this->year : '',
            'editor' => [
                'name' => $this->editor->cut_name,
                'updated_at' => $this->updated_at->format('d.m.Y (H:i)'),
            ],
        ];
    }
}
