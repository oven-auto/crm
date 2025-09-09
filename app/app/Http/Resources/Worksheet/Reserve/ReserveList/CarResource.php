<?php

namespace App\Http\Resources\Worksheet\Reserve\ReserveList;

use App\Helpers\Url\WebUrl;
use App\Http\Resources\Car\CarPriorityResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
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
            'stock_analysis' => $this->priority->id ? new CarPriorityResource($this->priority->sale_priority) : null,
            'id'                    => $this->id,
            'year'                  => $this->year,
            'brand'                 => $this->brand->name,
            'mark'                  => $this->mark->name,
            'complectation_code'    => $this->complectation->code,
            'complectation_id'      => $this->complectation->id,
            'complectation'         => $this->complectation->name,
            'motor_size'            => $this->complectation->motor->size,
            'motor_power'           => $this->complectation->motor->power,
            'motor_transmission'    => $this->complectation->motor->transmission->acronym,
            'motor_driver'          => $this->complectation->motor->driver->acronym,
            'body'                  => $this->complectation->bodywork->name,
            'vehicle'               => $this->complectation->vehicle->name,
            'color'                 => [
                'name' => $this->color->name ?? '',
                'code' => $this->color->base->web ?? '',
                'image' => $this->ImageURL,
            ],
            'logistic_status'       => $this->currentCarState(),
            'vin'                   => $this->vin,
            'order_number'          => $this->order->order_number ?? '',
            'marker'                => $this->marker->marker->id ? [
                'name' => $this->marker->marker->name,
                'text_color' => $this->marker->marker->text_color,
                'body_color' => $this->marker->marker->body_color,
                'description' => $this->marker->marker->description,
            ]:[],
            'pts'                   => $this->hasPTS(),
            'file' => [
                'url' => $this->complectation->file ? WebUrl::make_link($this->complectation->file->file) : '',
                'author' => $this->complectation->file && $this->complectation->file->author ? $this->complectation->file->author->cut_name : '',
            ],
        ];
    }
}
