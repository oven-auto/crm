<?php

namespace App\Http\Resources\Service;

use App\Http\Resources\Services\ServiceCategoryResource;
use App\Http\Resources\User\UserSmallResource;
use App\Http\Resources\Worksheet\Reserve\ReserveList\ClientResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'category'          => new ServiceCategoryResource($this->category),
            'author'            => new UserSmallResource($this->author),
            'providers'         => ClientResource::collection($this->providers),
            'updated_at'        => $this->updated_at->format('d.m.Y (H:i)'),
            'applicability'     => $this->applicabilities,
            'name'              => $this->name,
            'cost'              => $this->calculation->cost             ?? 0,
            'company_award'     => $this->calculation->company_award    ?? 0,
            'design_award'      => $this->calculation->design_award     ?? 0,
            'sale_award'        => $this->calculation->sale_award       ?? 0,
            'reminder'          => $this->prolongation->reminder        ?? 0,
            'manager'           => $this->prolongation->manager_id      ?? 0,
            'trash'             => $this->deleted_at ? 1 : 0,
            'prolongation'      => isset($this->prolongation->id) ? [
                'template' => $this->prolongation->template_id,
                'prolongator' => $this->prolongation->prolongator_id,
                'prolongation' => $this->prolongation->prolongation,
            ] : [],
        ];
    }
}
