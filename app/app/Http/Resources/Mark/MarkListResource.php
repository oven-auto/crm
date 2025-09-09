<?php

namespace App\Http\Resources\Mark;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Mark\MarkIconResource;
use App\Http\Resources\BodyWork\BodyWorkResource;
use App\Http\Resources\Mark\MarkBaseComplectationResource;

class MarkListResource extends JsonResource
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
            'name' => $this->name,
            'prefix' => $this->prefix,
            'id' => $this->id,
            'body_work_id' => $this->body_work_id,
            'slug' => $this->slug,
            'sort' => $this->sort,
            'status' => $this->status,
            'icon' => new MarkIconResource($this->icon),
            'bodywork' => new BodyWorkResource($this->bodywork),
            'basecomplectation' => new MarkBaseComplectationResource($this->basecomplectation),
            'brand' => new \App\Http\Resources\Brand\BrandResource($this->brand),
        ];
    }
}
