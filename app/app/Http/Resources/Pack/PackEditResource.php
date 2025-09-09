<?php

namespace App\Http\Resources\Pack;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Device\DeviceResource;

class PackEditResource extends JsonResource
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
                    'brand_id' => $this->brand_id,
                    'code' => $this->code,
                    'colored' => $this->colored,
                    'complectation_id' => $this->complectation_id,
                    'id' => $this->id,
                    'name' => $this->name,
                    'price' => $this->price,
                    'devices' => $this->devices->pluck('id'),
                    'marks' => $this->marks->pluck('id')
                ],
                'status' => 1,
                'message' => $this->isCreate() ? 'Опция создана' : 'Опция изменена'
            ];
        else
            return [
                'status' => 0,
                'message' => 'Такой опции не существует'
            ];
    }
}
