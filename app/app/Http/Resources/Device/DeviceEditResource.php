<?php

namespace App\Http\Resources\Device;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Brand\BrandEditCollectionResource;
use App\Http\Resources\Device\Filter\FilterEditResource;
use App\Http\Resources\Device\Type\TypeEditResource;
use App\Http\Resources\Device\Image\ImageEditResource;

class DeviceEditResource extends JsonResource
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
                    'device_filter_id' => $this->device_filter_id,
                    'device_type_id' => $this->device_type_id,
                    'id' => $this->id,
                    'name' => $this->name,
                    'image_count' => $this->image_count,
                    'brands' => ($this->brands->pluck('id')),
                    'filter' => ($this->filter),
                    'install_target' => $this->install_target,
                    'tuning' => $this->tuning,
                    'type' => ($this->type),
                    'image' => ($this->image->url_image),
                ],
                'status' => 1,
                'message' => $this->isCreate() ? 'Оборудование '.$this->name.' создано' : 'Оборудование '.$this->name.' изменено'
            ];
        else
            return [
                'status' => 0,
                'message' => 'Такого оборудование не существует'
            ];
    }
}
