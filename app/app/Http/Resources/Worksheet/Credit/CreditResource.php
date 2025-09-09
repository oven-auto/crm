<?php

namespace App\Http\Resources\Worksheet\Credit;

use App\Http\Resources\Credit\Content\CreditContentCollection;
use App\Http\Resources\Credit\Content\CreditContentResource;
use App\Http\Resources\Credit\Tactic\TacticResource;
use App\Http\Resources\User\UserSmallResource;
use App\Http\Resources\Worksheet\Reserve\ReserveList\ClientResource;
use App\Http\Resources\Worksheet\Service\ServiceResource;
use App\Services\Car\CarGeneral\CarGeneralService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreditResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'actuality'             => $this->actuality,
            'car' => CarGeneralService::make($this->car->carable),
            'id'                    => $this->id,
            'debtor'                => new ClientResource($this->debtor),
            'creditor'              => new ClientResource($this->creditor),
            'tactic'    => new TacticResource($this->tactic),
            'worksheet_id' => $this->worksheet_id,

            'period' => $this->calculation->period ?? 0,
            'cost' => $this->calculation->cost ?? 0,
            'first_pay' => $this->calculation->first_pay ?? 0,
            'month_pay' => $this->calculation->month_pay ?? 0,
            'simple' => $this->calculation->simple ?? 0,

            'status' => $this->status ? [
                'id' => $this->status->id,
                'name' => $this->status->name,
            ] : [],
            'author' => new UserSmallResource($this->author),

            //ПРИМЕРНОЕ НАПОЛНЕНИЕ
            'approximates' => $this->approximates->map(function($item){
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                ];
            }),

            //CONTRACT
            'register_at' => $this->contract ? ($this->contract->register_at->format('d.m.Y') ?? '') : '',
            
            'decorator' => $this->contract ? new UserSmallResource($this->contract->decorator) : [],

            'services' => ServiceResource::collection($this->services),

            'award' => $this->award->sum ?? 0,

            'award_complete' => $this->award->completed ?? 0,

            'close' => (int) $this->close,

            'deduction' => $this->deduction->sum ?? 0,

            'updated_at' => $this->updated_at->format('d.m.Y (H:i)'),

            'created_at' => $this->created_at->format('d.m.Y (H:i)'),

            'creator' => new UserSmallResource($this->creator),

            'state'                => $this->state->state,  
            
            'broker_deal' => (int)$this->broker_deal,

            'content' => CreditContentResource::collection($this->content),
        ];
    }
}
