<?php

namespace App\Http\Resources\Credit;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Credit\CreditMarkResource;

class CreditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name'          => $this->name,
            'banner'        => $this->banner,
            'rate'          => $this->rate,
            'pay'           => $this->pay,
            'period'        => $this->period,
            'contribution'  => $this->contribution,
            'begin_at'      => $this->begin_at,
            'end_at'        => $this->end_at,
            'disclaimer'    => $this->disclaimer,
            'status'        => $this->status,
            'marks'         => CreditMarkResource::collection($this->marks)
        ];
    }
}


