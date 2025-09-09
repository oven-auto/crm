<?php

namespace App\Http\Resources\Client\Car;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientCarEditResource extends JsonResource
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
                'brand_id' => $this->brand_id,
                'mark_id' => $this->mark_id,
                'body_work_id' => $this->body_work_id,
                'vin' => $this->vin ? $this->vin : '',
                'odometer' => $this->odometer ? $this->odometer : '',
                'register_plate' => $this->register_plate ? $this->register_plate : '',
                'year' => $this->year ? $this->year : '',
                'motor_size' => $this->motor_size,
                'motor_power' => $this->motor_power,
                'motor_transmission_id' => $this->motor_transmission_id,
                'motor_driver_id' => $this->motor_driver_id,
                'motor_type_id' => $this->motor_type_id,
                'color_id' => $this->color_id,
                'vehicle_type_id' => $this->vehicle_type_id,
                'editor' => [
                    'name' => $this->editor->cut_name,
                    'updated_at' => $this->updated_at->format('d.m.Y (H:i)'),
                ],
            ],
            'success' => 1
        ];
    }
}
