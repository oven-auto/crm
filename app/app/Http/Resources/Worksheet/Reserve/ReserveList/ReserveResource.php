<?php

namespace App\Http\Resources\Worksheet\Reserve\ReserveList;

use Illuminate\Http\Resources\Json\JsonResource;

class ReserveResource extends JsonResource
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
            'off_date' => $this->car->getLogisticDates('off_date'),
            'lising' => $this->getLisingerName(),
            'id' => $this->id,
            'state' => $this->getStatus(),
            'reserve_date' =>  [
                'date' => $this->created_at->format('d.m.Y'),
                'author' => $this->author->cut_name
            ],

            'car' => new CarResource($this->car),
            'trade_marker' => [
                'name' => $this->car->trade_marker->marker->name,
                'text_color' => $this->car->trade_marker->marker->text_color,
            ],

            'worksheet' => [
                'id' => $this->worksheet->id,
                'executors' => $this->worksheet->executors->map(function ($item) {
                    return [
                        'name' => $item->cut_name,
                        'type' => $this->worksheet->isAuthor($item->id) ? 'Автор' : 'Участник',
                        'id' => $item->id,
                    ];
                })
            ],

            'client' => new ClientResource($this->worksheet->client),

            'sale_date' => $this->sale ? [
                'date' => $this->sale->date_at->format('d.m.Y'),
                'author' => $this->sale->decorator->cut_name
            ] : [],

            'issue_date' => $this->issue ? [
                'date' => $this->issue->date_at->format('d.m.Y'),
                'author' => $this->issue->decorator->cut_name
            ] : [],

            'contract' => [
                'pdkp'         => [
                    'offer_date' => $this->contract->pdkpOfferDate,
                    'delivery_at' => $this->contract->PdkpDeliveryDate,
                    'decorator' => $this->contract->pdkp_decorator->cut_name,
                ],
                'dkp'          => [
                    'offer_date' => $this->contract->dkpOfferDate,
                    'decorator' => $this->contract->dkp_decorator->cut_name,
                    'closed_at' => $this->contract->DkpCloseDate,
                ],
                'payment' => $this->payments->count() ? [
                    'amount'    => $this->getPaymentSum(),
                    'balance'   => $this->getDebt(),
                ] : [],
            ],

            'tradeins' => [
                'contract' => $this->tradeins->map(function ($item) {
                    return [
                        'year'          => $item->year,
                        'brand'         => $item->brand->name,
                        'mark'          => $item->mark->name,
                        'odometer'      => $item->odometer,
                        'vin'           => $item->vin,
                        'created_at'    => $item->created_at->format('d.m.Y'),
                    ];
                }),
                'redemption' => $this->worksheet->redemptions->map(function ($item) {
                    return [
                        'year'          => $item->client_car->year,
                        'brand'         => $item->client_car->brand->name,
                        'mark'          => $item->client_car->mark->name,
                        'odometer'      => $item->client_car->odometer,
                        'vin'           => $item->client_car->vin,
                        'created_at'    => $item->created_at->format('d.m.Y'),
                        'status'        => 1,
                        //'status'        => $item->control_point()->title,
                    ];
                }),
            ],

            'cost' => [
                'status'    => [
                    'state' => $this->isFixedCost(),
                    'date'  => $this->getCostDate(),
                ],
                'base'                  => $this->getComplectationCost(),
                'option'                => $this->getOptionCost(),
                'over'                  => $this->getOverCost(),
                'tuning'                => $this->car->getTuningPrice(),
                'gift'                  => $this->car->getGiftPrice(),
                'full'                  => $this->getTotalCost(),
                'contract_cost'         => $this->getFullCost(),
                'sale_sum'              => $this->getSaleSum(),
                'total_cost'            => $this->getTotalCost(),
            ],

            'purchase' => [
                'ransom_date'           => $this->car->getRansomDate(),
                'purchase'              => $this->car->getPurchase(),
                'detailing_cost'        => $this->car->getPurchaseWithDelivery(),
            ],

            'delivery' => [
                'provider'              => $this->car->provider->cut_name,
                'delivery_term'         => [
                    'term_name' => $this->car->delivery_terms->term->name ?? '',
                    'deposit_name' => $this->car->collector->collector->name ?? '',
                    'color'         => $this->car->delivery_terms->term->text_color,
                ],
                'order_type'            => [
                    'name'          => $this->car->order_type ? $this->car->order_type->type->name : '',
                    'text_color'    => $this->car->order_type ? $this->car->order_type->type->text_color : '',
                    'body_color'    => $this->car->order_type ? $this->car->order_type->type->body_color : '',
                ],
            ],

            'report' => [
                'off_date' =>  $this->car->getStateByName('off_date'),
                'off_invalid' => $this->car->disable_off,
                'type' => [
                    'status' => $this->getReserveReportStatus(),
                    'title' => $this->getReserveReportString(),
                ],
            ],
        ];
    }
}
