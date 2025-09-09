<?php

namespace App\Http\Resources\UsedCar;

use Illuminate\Http\Resources\Json\JsonResource;

class UsedCarItemResource extends JsonResource
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
            'created_at' => $this->created_at->format('d.m.Y'),
            'redemption_id' => $this->wsm_redemption_car_id,
            'brand' => $this->brand ? [
                'id' => $this->brand->id,
                'name' => $this->brand->name,
            ] : [],
            'mark' => $this->mark ? [
                'id' => $this->mark->id,
                'name' => $this->mark->name,
            ] : [],
            'agent' => $this->agent ? [
                'id' => $this->agent->id,
                'name' => $this->agent->full_name,
            ] : [],
            'bodywork' => $this->bodywork ? [
                'id' => $this->bodywork->id,
                'name' => $this->bodywork->name,
            ] : [],
            'color' => $this->color ? [
                'id' => $this->color->id,
                'name' => $this->color->name,
            ] : [],
            'author' => $this->author ? [
                'id' => $this->author->id,
                'name' => $this->author->cut_name,
            ] : [],
            'vehicle' => $this->vehicle ? [
                'id' => $this->vehicle->id,
                'name' => $this->vehicle->acronym,
            ] : [],
            'year' => $this->year,
            'odometer' => $this->odometer,
            'vin' => $this->vin,
            'register_plate' => $this->register_plate,
            'purchase_price' => $this->purchase_price,
            'motor' => [
                'size' => $this->motor_size,
                'power' => $this->motor_power,
                'driver' => $this->driver ? [
                    'id' => $this->driver->id,
                    'name' => $this->driver->acronym,
                ] : [],
                'transmission' => $this->transmission ? [
                    'id' => $this->transmission->id,
                    'name' => $this->transmission->acronym,
                ] : [],
                'type' => $this->type ? [
                    'id' => $this->type->id,
                    'name' => $this->type->name,
                ] : [],
            ]
        ];
    }
}
