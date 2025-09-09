<?php

namespace App\Http\Resources\Car\Complectation;

use Illuminate\Http\Resources\Json\JsonResource;

class ComplectationListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'deleted' => $this->deleted_at ? 1 : 0,
            'code' => $this->code.' '.($this->deleted_at ? 'Архивная' : ''),
            'created_at' => $this->created_at->format('d.m.Y'),
            'saled_cars' => $this->saled_cars,
            'active_cars' => $this->active_car,
            'deleted' => $this->deleted_at ? 1 : 0,
            'alias' => ($this->alias && $this->alias->alias) ? $this->alias->alias->name : '',
            'price' => ($this->current_price && $this->current_price->id) ? [
                'id' => $this->current_price->id,
                'price' => $this->current_price->price,
                'author' => $this->current_price->curprice->author->cut_name,
                'begin_at' => $this->current_price->begin_at->format('d.m.Y'),
                'created_at' => $this->current_price->curprice->created_at->format('d.m.Y')
            ] : [],
            'file' => $this->file ? 1 : 0,
            'factory' => $this->factory->only(['city','country']),
            'motor' => $this->motor ? [
                'power' => $this->motor->power,
                'size' => $this->motor->size,
                'transmission' => $this->motor->transmission->acronym, 
                'driver' => $this->motor->driver->acronym, 
                'type' => $this->motor->type->acronym,
            ] : [],
            'bodywork' => $this->bodywork->name,
            'vehicle' => $this->vehicle->name,
            'model' => $this->mark->name,
            'brand' => $this->mark->brand->name,
            'author' => $this->author->cut_name,
        ];
    }
}
