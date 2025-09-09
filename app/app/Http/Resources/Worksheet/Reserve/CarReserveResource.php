<?php

namespace App\Http\Resources\Worksheet\Reserve;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Car\CarPriorityResource;

class CarReserveResource extends JsonResource
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
            'id'            => $this->id,
            'year'                  => $this->year,
            'brand'                 => $this->brand->name,
            'mark'                  => $this->mark->name,
            'order_number'          => $this->order->order_number,
            'color'                 => $this->color->name,
            'vin'                   => $this->vin,
            'complectation' => [
                'name'              => $this->complectation->name,
                'code'              => $this->complectation->code,
                'bodywork'          => $this->complectation->bodywork->name,
                'motor' => [
                    'size'          => $this->complectation->motor->size,
                    'power'         => $this->complectation->motor->power,
                    'tranmission'   => $this->complectation->motor->transmission->acronym,
                    'driver'        => $this->complectation->motor->driver->acronym,
                ],
            ],

            'status'                => $this->currentCarState(),
            //'sale_date'             => $this->getLogisticAuthors('sale_date'),
            //'off_date'              => $this->getLogisticAuthors('off_date'),
            'ransom_date'           => $this->getLogisticAuthors('ransom_date'),
        ];
    }
}
