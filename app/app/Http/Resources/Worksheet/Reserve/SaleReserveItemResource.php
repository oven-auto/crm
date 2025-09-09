<?php

namespace App\Http\Resources\Worksheet\Reserve;

use App\Http\Resources\User\UserSmallResource;
use App\Http\Resources\Worksheet\Reserve\ReserveList\ClientResource;
use App\Models\WsmReserveNewCar;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleReserveItemResource extends JsonResource
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
            'reserve_id' => $this->modulable_id,
            'worksheet_id' => $this->worksheet_id,
            'worksheet_status' => $this->worksheet->status,
            'type' => [
                'id' => $this->type->id,
                'name' => $this->type->name,
                'returnable' => $this->type->returnable,
                'salon' => $this->type->salon->name,
            ],
            'author' => [
                'id' => $this->author->id,
                'name' => $this->author->cut_name,
            ],
            'sum' => $this->sum ? [
                'amount' => $this->sum->amount,
                'author' => $this->sum->author->cut_name,
                'created' => $this->sum->updated_at->format('d.m.Y'),
            ] : [],
            'reparation' => $this->reparation ? [
                'amount' => $this->reparation->amount,
                'author' => $this->reparation->author->cut_name,
                'created' => $this->reparation->updated_at->format('d.m.Y'),
            ] : [],
            'reparation_date' => $this->reparation_date ? [
                'date' => $this->reparation_date->date_at->format('d.m.Y'),
                'author' => $this->reparation_date->author->cut_name,
                'created' => $this->reparation_date->updated_at->format('d.m.Y'),
            ] : [],
            'base' => $this->base ? [
                'base' => $this->base->base,
                'author' => $this->base->author->cut_name,
                'created' => $this->base->updated_at->format('d.m.Y'),
            ] : [],
            'status' => [
                'check' => $this->check->status,
                'author' => new UserSmallResource($this->check->author),
                'created_at' => $this->check->updated_at->format('d.m.Y'),
            ],
            'client' => new ClientResource($this->worksheet->client),
            'newcar' => ($this->modulable && $this->modulable::class == WsmReserveNewCar::class) ? [
                'id'        => $this->modulable->car_id,
                'brand'     => $this->modulable->car->brand->name,
                'mark'      => $this->modulable->car->mark->name,
                'vin'       => $this->modulable->car->vin,
                'status'    => $this->modulable->car->getStatusCarForDiscountList()
            ] : [],
        ];
    }
}
