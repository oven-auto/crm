<?php

namespace App\Http\Resources\Car\Option;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OptionIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $res = $this['res'];
        $car = $this['car'];

        return [
            'data' => [
                'options' => $res,
                'car' => $car ? [
                    'vin' => $car->vin ?? '',
                    'brand' => $car->brand->name,
                    'mark' => $car->mark->name,
                    'vehicle_type' => $car->complectation->vehicle->name,
                    'body' => $car->complectation->bodywork->name,
                ] : [],
            ],
            'success' => 1
        ];
    }
}
