<?php

namespace App\Http\Resources\Worksheet\Reserve;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractItemResource extends JsonResource
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
            'id'                    => $this->id,
            'pdkp_offer_at'         => $this->pdkp_offer_date,
            'pdkp_delivery_at'      => $this->pdkp_delivery_date,
            'pdkp_closed_at'        => $this->pdkp_close_date,
            'pdkp_days'              => $this->pdkdDays(),
            'pdkp_decorator'        => [
                'name' => $this->pdkp_decorator->cut_name,
                'id' => $this->pdkp_decorator->id,
            ],

            'dkp_offer_at'          => $this->dkp_offer_date,
            'dkp_closed_at'         => $this->dkp_close_date,

            'dkp_decorator'         => [
                'name' => $this->dkp_decorator->cut_name,
                'id'      => $this->dkp_decorator_id,
            ],

            'author'                => [
                'name' => $this->author->cut_name,
                'id' => $this->author->id,
            ],
            'updated_at'            => $this->updated_date,
        ];
    }
}
