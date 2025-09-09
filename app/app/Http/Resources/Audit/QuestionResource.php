<?php

namespace App\Http\Resources\Audit;

use App\Http\Resources\User\UserSmallResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
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
            'audit_id' => $this->audit_id,                
            'name' => $this->name,
            'text' => $this->text,
            'is_stoped' => $this->is_stoped,
            'created_at' => $this->created_at->format('d.m.Y'),
            'author' => new UserSmallResource($this->author),
            'deleted_at' => $this->deleted_at,
            'trash' => $this->deleted_at ? 1 : 0,
            'weight' => round($this->getWeight(),1),
            'answers' => [
                'positive'  => $this->answers->positive,
                'negative'  => $this->answers->negative,
                'neutral'   => $this->answers->neutral,
            ],
        ];
    }
}
