<?php

namespace App\Http\Resources\Mark;

use Illuminate\Http\Resources\Json\JsonResource;

class MarkEditResource extends JsonResource
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
                    'name' => $this->name,
                    'prefix' => $this->prefix,
                    'id' => $this->id,
                    'body_work_id' => $this->body_work_id,
                    'slug' => $this->slug,
                    'brand_id' => $this->brand_id,
                    'country_factory_id' => $this->country_factory_id,
                    'show_driver' => $this->show_driver,
                    'show_toxic' => $this->show_toxic,
                    'sort' => $this->sort,
                    'status' => (integer)$this->status,

                    'bodywork' =>           new \App\Http\Resources\BodyWork\BodyWorkListResource($this->bodywork),
                    'basecomplectation' =>  new \App\Http\Resources\Mark\MarkBaseComplectationResource($this->basecomplectation),
                    'brand' =>              new \App\Http\Resources\Brand\BrandResource($this->brand),

                    'info' =>               new \App\Http\Resources\Mark\MarkInfoResource($this->info),
                    'icon' =>               $this->icon->image_date,
                    'banner' =>             $this->banner->image ? $this->banner->image_date : '',
                    'properties' =>         \App\Http\Resources\Mark\MarkPropertiesResource::collection($this->properties),
                    'markcolors' =>         \App\Http\Resources\Mark\MarkColorResource::collection($this->markcolors),
                    'document' =>           new \App\Http\Resources\Mark\DocumentResource($this->document),
                    'colors' => \App\Http\Resources\Mark\ColorImageResource::collection($this->markcolors),
                ],
                'status' => 1,
                'message' => $this->isCreate() ? 'Модель создана' : 'Модель изменена'
            ];
        else
            return [
                'status' => 0,
                'message' => 'Такой Производитель не существует'
            ];
    }
}
