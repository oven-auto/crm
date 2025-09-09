<?php

namespace App\Http\Resources\Car\Car;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Car\CarPriorityResource;

class CarSupportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $support = [
            'complectation' => new CarComplectationResource($this),
            'stock_analysis' => $this->priority->id ? new CarPriorityResource($this->priority->sale_priority) : null,
            'status' => $this->currentCarState(),

            'brand' => $this->brand->name,

            'mark' => $this->mark->name,

            'car_author' => [
                'author'                    => $this->author->cut_name,
                'created_at'                => $this->created_at->format('d.m.Y (H:i)'),
            ],

            'purchase_author' => [
                'author' => $this->purchase->author->cut_name ?? '',
                'created_at' => $this->purchase->id ? $this->purchase->updated_at->format('d.m.Y (H:i)') : '',
            ],

            'provider' => [
                'provider_name' => $this->provider->cutName,
                'provider_id' => $this->provider->provider_id
            ],

            'order_type' => [
                'author' => $this->order_type->author->cut_name ?? '',
                'created_at' => $this->order_type ? $this->order_type->updated_at->format('d.m.Y (H:i)') : '',
            ],

            'collector' => [
                'author' => $this->collector->author->cut_name ?? '',
                'created_at' => $this->collector ? $this->collector->updated_at->format('d.m.Y (H:i)') : '',
            ],

            'delivery_terms' => [
                'created_at' => $this->delivery_terms->updated_at ? $this->delivery_terms->updated_at->format('d.m.Y (H:i)') : '',
                'author' => $this->delivery_terms->author->cut_name ?? '',
            ],

            'coast' => [
                'base'      => $this->getComplectationPrice(),
                'option'    => $this->getOptionPrice(),
                'over'      => $this->getOverPrice(),
                'tuning'    => $this->getFullTuningPrice(),
                'full'      => $this->getCarPrice(),
                'sale'      => $this->getReserveSale(),
            ],

            'owner' => $this->owner ? [
                'client' => [
                    'name' => $this->owner->client->full_name,
                    'phone' => $this->owner->client->isPerson() ? $this->owner->client->phones->first()->phone : $this->owner->client->inn->number,
                    'id' => $this->owner->client_id
                ],
                'author' => [
                    'name' => $this->owner->author->cut_name,
                    'created_at' => $this->owner->created_at->format('d.m.Y (H:i)')
                ]
            ] : [],

            'worksheet' => $this->getWorksheetId() ? [
                'id' => $this->getWorksheetId(),
                'status' => $this->reserve->worksheet->status->name,
            ] : [],

            'client_status' =>  $this->getReserveStatus(),

            'gift_price' => $this->gift_price ? [
                    'author' => $this->gift_price->author->cut_name,
                    'created_at' => $this->gift_price->updated_at->format('d.m.Y (H:i)'),
                ] : [],
            
            'part_price' => $this->part_price ? [
                    'author' => $this->part_price->author->cut_name,
                    'created_at' => $this->part_price->updated_at->format('d.m.Y (H:i)'),
                ] : [],
            
            'tuning_price' => $this->tuning_price ? [
                    'author' => $this->tuning_price->author->cut_name,
                    'created_at' => $this->tuning_price->updated_at->format('d.m.Y (H:i)'),
                ] : [],

            'paid_date' => $this->paid_date ? [
                'created_at' => $this->paid_date->updated_at->format('d.m.Y (H:i)'),
                'author' => $this->paid_date->author->cut_name,
                'description' => 'Начало платного периода',
            ] : [],
            
            'control_paid_date' => $this->control_paid_date ? [
                'created_at' => $this->control_paid_date->updated_at->format('d.m.Y (H:i)'),
                'author' => $this->control_paid_date->author->cut_name,
                'description' => 'Контроль срока оплаты',
            ] : [],

            'image' => $this->ImageURL,

        ];

        $support = array_merge($support, $this->getLogisticAuthors()->collapse()->toArray());

        return $support;
    }
}
