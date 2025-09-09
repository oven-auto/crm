<?php

namespace App\Http\Resources\Worksheet\Reserve;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentSaveResource extends JsonResource
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
            'payment' => [
                'name' => $this->payment->name,
                'id' => $this->payment->id,
            ],
            'amount' => $this->amount,
            'date_at' => $this->date_at->format('d.m.Y'),
            'author' => [
                'name' => $this->author->cut_name,
                'id' => $this->author->id
            ],
        ];
    }
}
