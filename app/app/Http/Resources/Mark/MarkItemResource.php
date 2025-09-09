<?php

namespace App\Http\Resources\Mark;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Mark\MarkBannerResource;
use App\Http\Resources\Mark\MarkPropertiesResource;
use App\Http\Resources\Mark\MarkInfoResource;
use App\Http\Resources\Mark\MarkColorResource;
use App\Http\Resources\Brand\BrandResource;
use App\Http\Resources\BodyWork\BodyWorkResource;

class MarkItemResource extends JsonResource
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
                'banner' => new MarkBannerResource($this->banner),
                'body_work_id' => $this->body_work_id,
                'bodywork' => new BodyWorkResource($this->bodywork),
                'brand' => new BrandResource($this->brand),
                'brand_id' => $this->brand_id,
                'country_factory_id' => $this->country_factory_id,
                'id' => $this->id,
                'info' => new MarkInfoResource($this->info),
                'markcolors' => MarkColorResource::collection($this->markcolors),
                'name' => $this->name,
                'prefix' => $this->prefix,
                'properties' => MarkPropertiesResource::collection($this->properties),
                'slug' => $this->slug,
                'sort' => $this->sort,
                'status' => $this->status,
            ],
            'status' => $this->count() ? 1 : 0
        ];
    }
}
