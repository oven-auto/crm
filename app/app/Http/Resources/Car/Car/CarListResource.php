<?php

namespace App\Http\Resources\Car\Car;

use App\Http\Resources\Car\CarPriorityResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Worksheet\Reserve\ReserveList\CarResource;

class CarListResource extends JsonResource
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
            
            'stock_analysis' => $this->priority->id ? new CarPriorityResource($this->priority->sale_priority) : null,

            'state' => $this->getReserveStatus(),

            'trade_marker' => $this->trade_marker ? [
                'name'          => $this->trade_marker->marker->name,
                'text_color'    => $this->trade_marker->marker->text_color,
                'body_color'    => $this->trade_marker->marker->body_color,
                'description'   => $this->trade_marker->marker->description,
            ] : [],

            'car' => new CarResource($this),

            'worksheet' => [
                'id' => $this->getWorksheetId(),
            ],

            'cost' => [
                'status'    => [
                    'state' => (int)$this->isFixed(),
                ],
                'base'                  => $this->getComplectationPrice(),//кузов
                'option'                => $this->getOptionPrice(),//опции
                'over'                  => $this->getOverPrice(),//переоценка
                'tuning'                => $this->getTuningPrice(),//тюнинг
                'gift'                  => $this->getGiftPrice(),//gift
                'full'                  => $this->getCarPrice(),
                'sale_sum'              => $this->getReserveSale(),//скидка
            ],

            'purchase' => [
                'ransom_date'           => $this->getRansomDate(),
                'purchase'              => $this->getPurchase(),
                'detailing_cost'        => $this->getPurchaseWithDelivery(),
            ],

            'delivery' => [
                'provider'              => $this->provider->cut_name,
                'delivery_term'         => [
                    'term_name' => $this->delivery_terms->term->name ?? '',
                    'deposit_name' => $this->collector->collector->name ?? '',
                    'color'         => $this->delivery_terms->term->text_color,
                ],
                'order_type'            => [
                    'name' => $this->order_type ? $this->order_type->type->name : '',
                    'text_color' => $this->order_type ? $this->order_type->type->text_color : '',
                    'body_color' => $this->order_type ? $this->order_type->type->body_color : '',
                ],
            ],

            'report' => [
                'off_date' => $this->getStateByName('off_date'),
                'off_invalid' => $this->disable_off,
                'type' => [
                    'status' => $this->getReportTypeStatus(),
                    'title' => $this->getReportTypeString(),
                ],
            ],
        ];
    }
}
