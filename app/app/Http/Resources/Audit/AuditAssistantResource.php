<?php

namespace App\Http\Resources\Audit;

use App\Http\Resources\User\UserSmallResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditAssistantResource extends JsonResource
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
                'id'            => $this->id,
                'audit_id'      => $this->audit_id,
                'result'        => json_decode($this->result,1),
                'author'        => new UserSmallResource($this->author),
                'created_at'    => $this->created_at->format('d.m.Y'),
                'updated_at'    => $this->updated_at->format('d.m.Y'),
            ],
            'success' => 1,
        ];
    }
}
