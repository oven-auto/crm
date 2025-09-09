<?php

namespace App\Http\Resources\Complectation;

use Illuminate\Http\Resources\Json\JsonResource;

class ComplectationEditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        foreach($this->markColor as $itemMarkColor) {
            $installColorPack = [];
            foreach($this->colorPacks as $itemColorPack) {
                if($itemColorPack->id == $itemMarkColor->id)
                    $installColorPack[] = $itemColorPack->pivot->pack_id;
            }
            $itemMarkColor->installColorPack = $installColorPack;
        }

        if($this->id)
            return [
                'data' => [
                    'brand' => new \App\Http\Resources\Brand\BrandResource($this->brand),
                    'mark' => new \App\Http\Resources\Mark\MarkListResource($this->mark),
                    'motor' => new \App\Http\Resources\Motor\MotorListResource($this->motor),

                    'brand_id' => $this->brand_id,
                    'code' => $this->code,
                    'id' =>$this->id,
                    'lastmoderator' => $this->lastmoderator,
                    'mark_id' => $this->mark_id,
                    'motor_id' => $this->motor_id,
                    'name' => $this->name,
                    'parent_id' => $this->parent_id,
                    'price' => $this->price,
                    'price_status' => $this->price_status,
                    'sort' => $this->sort,
                    'status' => $this->status,

                    'devices' =>  $this->devices->pluck('id'),
                    'packs' =>  $this->packs->pluck('id'),

                    'install_colors' => $this->colors->pluck('id'),
                    'colors' => \App\Http\Resources\Complectation\ColorComplectationResource::collection($this->markColor, 123),
                ],
                'status' => 1,
                'message' => $this->isCreate() ? 'Комплектация создана' : 'Комплектация изменена'
            ];
        else
            return [
                'status' => 0,
                'message' => 'Такой комплектации не существует'
            ];
    }
}


