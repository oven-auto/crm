<?php

namespace App\Http\Resources\Director;

use App\Http\Resources\User\UserSmallResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReceiptResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'priority' => isset($this->reserve->car->priority->sale_priority->id) ? $this->reserve->car->priority->sale_priority->id : 0,
            'car_id' => $this->reserve->car->id,
            'worksheet_id' => $this->reserve->worksheet_id,
            'client' => $this->reserve->worksheet->client->full_name,
            'model' => $this->reserve->car->mark->name,
            'vin' => $this->reserve->car->vin,
            'price' => $this->reserve->getTotalCost(),
            'debit' => $this->reserve->getDebt(),
            
            'collector' => $this->reserve->car->collector ? $this->reserve->car->collector->collector->name : '',
            'comment' => isset($this->reserve->last_comment->id) ? $this->reserve->last_comment->text : '',
            'author' => new UserSmallResource($this->reserve->worksheet->author),

            'payment_amount'        => $this->amount ?? 0,
            'payment_date'          => isset($this->date_at) ? $this->date_at->format('d.m.Y') : '',
            'payment_type'          => $this->payment->name ?? '',

            'planned_payment_date'  => $this->reserve->planned_payment ? $this->reserve->planned_payment->date_at->format('d.m.Y') : '',
            'ransom_date' => $this->reserve->car->getRansomDate(),
            'issued_date' => $this->reserve->getIssueDate(),
            'sale_date' => $this->reserve->getSaleDate(),
            'lisinger' => $this->reserve->lisinger_name,
            'stock_date' => $this->reserve->car->getLogisticDateByKey('stock_date') ?? ''
        ];
    }
}
