<?php

namespace App\Http\Resources\Car\Option;

use Illuminate\Http\Resources\Json\JsonResource;

class OptionResource extends JsonResource
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
            'code' => $this->code,
            'price' => $this->price,
            'begin_at' => '',
            'mark' => $this->mark->name,
            'brand' => $this->brand->name,
            'trash' => (int)$this->trashed(),
            'author' => $this->author->cut_name,
            'created_at' => $this->created_at->format('d.m.Y',)
        ];
    }
}
