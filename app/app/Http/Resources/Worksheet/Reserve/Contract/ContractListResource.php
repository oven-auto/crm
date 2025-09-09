<?php

namespace App\Http\Resources\Worksheet\Reserve\Contract;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractListResource extends JsonResource
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
            'worksheet' => [
                'id' => $this->reserve->worksheet->id,
                'status' => $this->reserve->worksheet->status->name,
            ],
            'car' => [
                'id' => $this->reserve->car->id,
                'vin' => $this->reserve->car->vin,
                'brand' => $this->reserve->car->brand->name,
                'mark' => $this->reserve->car->mark->name,
                'trashed' => $this->reserve->car->deleted_at ? 1 : 0
            ],
            'client' => [
                'id' => $this->reserve->worksheet->client->id,
                'name' => $this->reserve->worksheet->client->full_name,
                'zone' => $this->reserve->worksheet->client->zone->name,
            ],
           'pdkp'         => [
                'offer_date' => $this->pdkpOfferDate,
                'delivery_at' => $this->PdkpDeliveryDate,
                'decorator' => $this->pdkp_decorator->cut_name,
            ],
            'dkp'          => [
                'offer_date' => $this->dkpOfferDate,
                'decorator' => $this->dkp_decorator->cut_name,
                'closed_at' => $this->DkpCloseDate,
            ],
            'arrears' => [
                'debit' => $this->getDebtorArrears(),
                'credit' => $this->getCreditorArrears(),
            ],
            'sale_at' => '',

            'state' => $this->reserve->car->getStatusCarForDiscountList(),
        ];
    }
}
