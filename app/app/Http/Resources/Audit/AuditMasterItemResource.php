<?php

namespace App\Http\Resources\Audit;

use App\Http\Resources\User\UserSmallResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditMasterItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => [
                'id'                => $this->id,
                'trafic_id'         => $this->trafic_id,
                'audit_id'          => $this->audit_id,
                'author'            => new UserSmallResource($this->author),
                'manager'           => new UserSmallResource($this->trafic->manager),
                'positive_count'    => $this->positive_count ?? 0,
                'point'             => $this->point ?? 0,
                'positive'          => $this->positive ?? 0,
                'total'             => $this->total ?? 0,
                'result'            => json_decode($this->result, 1),
                'created_at'        => $this->created_at->format('d.m.Y (H:i)'),
                'updated_at'        => $this->updated_at->format('d.m.Y (H:i)'),
                'trash'             => $this->isDeleted(),
                'status'            => $this->status,
                'client_id'         => $this->trafic->worksheet->client->id,
                'worksheet_id'      => $this->trafic->worksheet->id,
            ],
            'success' => 1,
        ];
    }
}
