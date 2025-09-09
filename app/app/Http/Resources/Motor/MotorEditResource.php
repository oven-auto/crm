<?php

namespace App\Http\Resources\Motor;

use Illuminate\Http\Resources\Json\JsonResource;

class MotorEditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($this->id)
            return [
                'data' => [
                    'id' => $this->id,
                    'brand_id' => $this->brand_id,
                    'motor_type_id' => $this->motor_type_id,
                    'motor_transmission_id' => $this->motor_transmission_id,
                    'motor_driver_id' => $this->motor_driver_id,
                    'name' => $this->name,
                    'power' => $this->power,
                    'valve' => $this->valve,
                    'size' => $this->size,
                    'motor_toxic_id' => $this->motor_toxic_id,
                    'transmission_name' => $this->transmission_name,
                    'brand' => $this->brand,
                    'transmission'  => $this->transmission,
                    'type'  => $this->type,
                    'driver'  => $this->driver,
                    'toxic'  => $this->toxic,

                ],
                'status' => 1,
                'message' => $this->isCreate() ? 'Агрегат создан' : 'Агрегат изменен'
            ];
        else
            return [
                'status' => 0,
                'message' => 'Такого агрегата не существует'
            ];
    }
}
