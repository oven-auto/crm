<?php

namespace App\Http\Resources\Audit;

use App\Http\Resources\User\UserSmallResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditMasterListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'trafic_id' => $this->trafic_id,
            'created_at' => $this->created_at->format('d.m.Y (H:i)'),
            'updated_at' => $this->updated_at->format('d.m.Y (H:i)'),
            'audit' => [
                'id' => $this->audit->id,
                'name' => $this->audit->name,
                'appeal' => $this->audit->appeal->name,
                'complete' => $this->audit->complete,
                'questions_count' => $this->audit->questions_count,
            ],
            'salon' => $this->trafic->salon->name,
            'structure' => $this->trafic->structure->name,
            'manager' => new UserSmallResource($this->trafic->manager),
            'author' => new UserSmallResource($this->author),
            'point' => $this->point ?? 0,
            'positive'          => $this->positive ?? 0,
            'total'             => $this->total ?? 0,
            'status' => $this->status,
            'trash' => $this->isDeleted(),
            'count_response' => $this->getResponseCount(),
            'record' => $this->record ? 1 : 0,
            'completed' => $this->completed,
            'client_id' => $this->trafic->worksheet->client->id,
            'worksheet_id' => $this->trafic->worksheet->id,
        ];
    }
}
