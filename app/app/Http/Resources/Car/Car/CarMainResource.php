<?php

namespace App\Http\Resources\Car\Car;

use Illuminate\Http\Resources\Json\JsonResource;

class CarMainResource extends JsonResource
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
            'id'                        => $this->id,
            'collector_id'              => $this->collector->collector_id ?? '',
            'mark_id'                   => $this->mark_id, //model
            'brand_id'                  => $this->brand_id, //brand
            'complectation_id'          => $this->complectation_id, //complectation
            'color_id'                  => $this->color_id, //dealer color
            'year'                      => $this->year, //year
            'vin'                       => $this->vin, //vin
            'order_number'              => $this->order->order_number ?? '', //order number
            'marker_id'                 => $this->marker->marker_id ?? '', //маркер логиста
            'trade_marker_id'           => $this->trade_marker->trade_marker_id, //товарный признак
            'provider_id'               => $this->provider->provider_id, //поставщик
            'provider_name'             => $this->provider->cut_name, //поставщик
            'order_type_id'             => $this->order_type->order_type_id ?? '', //тип заказа
            'order_date'                => $this->getLogisticDates('order_date'), //заказа
            'plan_date'                 => $this->getLogisticDates('plan_date'), //планируемая
            'build_date'                => $this->getLogisticDates('build_date'), //фактическая
            'ready_date'                => $this->getLogisticDates('ready_date'), //готовность
            'request_date'              => $this->getLogisticDates('request_date'), //заявка на перевозку
            'shipment_date'             => $this->getLogisticDates('shipment_date'), //отгрузка
            'presale_date'              => $this->getLogisticDates('presale_date'), //предпродажка
            'stock_date'                => $this->getLogisticDates('stock_date'), //приемка
            'invoice_date'              => $this->getLogisticDates('invoice_date'), //приходная
            'ransom_date'               => $this->getLogisticDates('ransom_date'), //оплата поставщику
            'sale_date'                 => $this->getLogisticDates('sale_date'), //продажа
            'off_date'                  => $this->getLogisticDates('off_date'), //списание
            'technic_id'                => $this->technic->technic_id ?? '', //техник
            'audio_code'                => $this->audio->audio_code ?? '', //аудиокод
            'purchase_cost'             => $this->purchase->cost ?? '', //закуп
            'disable_sale'              => (bool)$this->disable_sale, //не учитывать в продаже
            'disable_off'               => (bool)$this->disable_off, //не учитывать в списании
            'options'                   => $this->options->pluck('id')->toArray(), //опции
            'comment'                   => $this->comment ? $this->comment->comment : '',

            'delivery_term_id'          => $this->delivery_terms->delivery_term_id ?? '',

            'detailing_costs'           => $this->detailing_costs->map(function ($item) { //себестоимость
                return [
                    'detailing_cost_id' => $item->detailing_cost_id,
                    'price' => $item->price,
                    'coefficient' => $item->coefficient
                ];
            }),
            
            'devices' => $this->tuning,
           
            'owner' => $this->owner->client_id ?? '',

            'tuning_price' => $this->tuning_price ? $this->getTuningPrice() : '',
            'gift_price' => $this->gift_price ? $this->getGiftPrice() : '',
            'part_price' => $this->part_price ? $this->getPartPrice() : '',

            'paid_date'             => $this->paid_date ? $this->paid_date->date_at->format('d.m.Y') : '',
            'control_paid_date'     => $this->control_paid_date ? $this->control_paid_date->date_at->format('d.m.Y') : '',
        ];
    }
}
