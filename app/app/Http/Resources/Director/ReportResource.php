<?php

namespace App\Http\Resources\Director;

use App\Http\Resources\User\UserSmallResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'priority' => isset($this->car->priority->sale_priority->id) ? $this->car->priority->sale_priority->id : 0,
            'car_id' => $this->car->id,
            'worksheet_id' => $this->worksheet_id,
            'client' => $this->worksheet->client->full_name,
            'model' => $this->car->mark->name,
            'vin' => $this->car->vin,
            'price' => $this->getTotalCost(),
            'debit' => $this->getDebt(),
            
            'collector' => $this->car->collector ? $this->car->collector->collector->name : '',
            'comment' => isset($this->last_comment->id) ? $this->last_comment->text : '',
            'author' => new UserSmallResource($this->worksheet->author),

            'payment_amount'        => $this->payments->first()->amount ?? 0,
            'payment_date'          => isset($this->payments->first()->date_at) ? $this->payments->first()->date_at->format('d.m.Y') : '',
            'payment_type'          => $this->payments->first()->payment->name ?? '',

            'planned_payment_date'  => $this->planned_payment ? $this->planned_payment->date_at->format('d.m.Y') : '',
            'ransom_date' => $this->car->getRansomDate(),
            'issued_date' => $this->getIssueDate(),
            'sale_date' => $this->getSaleDate(),
            'lisinger' => $this->lisinger_name,
            'stock_date' => $this->car->getLogisticDateByKey('stock_date') ?? ''
        ];
    }
}
