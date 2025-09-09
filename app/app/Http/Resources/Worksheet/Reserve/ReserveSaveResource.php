<?php

namespace App\Http\Resources\Worksheet\Reserve;

use App\Http\Resources\Client\NameIdResource;
use App\Http\Resources\UsedCar\UsedCarItemResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ReserveSaveResource extends JsonResource
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
            'lising'                    => $this->hasLisinger() ? new NameIdResource($this->getLisinger()) : [],
            'id'                        => $this->id,
            'created_at'                => $this->created_at->format('d.m.Y'),
            'worksheet_id'              => $this->worksheet_id,
            'author' => [
                'name'                  => $this->author->cut_name,
                'id'                    => $this->author->id
            ],
            
            'car'                       => new CarReserveResource($this->car),
            'contract'                  => $this->contract->id ? new ContractItemResource($this->contract) : [],
            'discounts'                 => SaleReserveItemResource::collection($this->discounts),
            'comments' => $this->last_comment->id ? [
                'text'                  => $this->last_comment->text,
                'id'                    => $this->last_comment->id,
                'author'                => $this->last_comment->author->cut_name,
                'created_at'            => $this->last_comment->created_at ? $this->last_comment->created_at->format('d.m.Y (H:i)') : '',
            ] : [],
            'price'                     => $this->getFullCost(),
            'payments'                  => PaymentSaveResource::collection($this->payments),
            'tradeins'                  => UsedCarItemResource::collection($this->tradeins),

            'issue_date' => $this->issue()->exists() ? [
                'author' => [
                    'name'                  => $this->issue->author->cut_name,
                    'id'                    => $this->issue->author->id
                ],
                'date_at' => $this->issue->date_at->format('d.m.Y'),
                'decorator' => [
                    'id' => $this->issue->decorator->id,
                    'name' => $this->issue->decorator->cut_name,
                ],
                'updated_at' => $this->issue->updated_at->format('d.m.Y'),
            ] : [],
            'sale_date' => $this->sale()->exists() ? [
                'author' => [
                    'name'                  => $this->sale->author->cut_name,
                    'id'                    => $this->sale->author->id
                ],
                'date_at' => $this->sale->date_at->format('d.m.Y'),
                'decorator' => [
                    'id' => $this->sale->decorator->id,
                    'name' => $this->sale->decorator->cut_name,
                ],
                'updated_at' => $this->sale->updated_at->format('d.m.Y'),
            ] : [],
            'trash' => $this->deleted_at ? 1 : 0,
        ];
    }
}
