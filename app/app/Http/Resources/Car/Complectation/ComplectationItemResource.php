<?php

namespace App\Http\Resources\Car\Complectation;

use App\Helpers\Url\WebUrl;
use Illuminate\Http\Resources\Json\JsonResource;

class ComplectationItemResource extends JsonResource
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
            'data' => [
                'id' => $this->id,
                'name' => $this->name,
                'code' => $this->code,
                'factory_id' => $this->factory_id,
                'brand_id' => $this->mark->brand->id,
                'mark_id' => $this->mark->id,
                'vehicle_type_id' => $this->vehicle_type_id,
                'size' => $this->motor->size,
                'power' => $this->motor->power,
                'motor_driver_id' => $this->motor->motor_driver_id,
                'motor_transmission_id' => $this->motor->motor_transmission_id,
                'motor_type_id' => $this->motor->motor_type_id,
                'body_work_id' => $this->body_work_id,
                'author' => $this->author->cut_name,
                'price' => $this->current_price->price ? [
                    'price' => $this->current_price->price,
                    'begin_at' => $this->current_price->begin_at->format('d.m.Y')
                ] : '',
                'last_editor' => [
                    'author' => $this->last_history->author->cut_name,
                    'date' => $this->last_history->created_at ? $this->last_history->created_at->format('d.m.Y (H:i)') : '',
                ],
                'file' => [
                    'url' => $this->file ? WebUrl::make_link($this->file->file) : '',
                    'author' => $this->file ? $this->file->author->cut_name : '',
                ],
                'trash' => (int)$this->trashed(),
                'alias_id' => $this->alias->mark_alias_id ?? 0,
            ],
            'success' => 1,
        ];
    }
}
