<?php

namespace App\Http\Resources\Audit;

use App\Http\Resources\User\UserSmallResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditResource extends JsonResource
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
                'name' => $this->name,
                'author' => new UserSmallResource($this->author),
                'appeal' => $this->appeal->id,
                'bonus' => $this->bonus,
                'malus' => $this->malus,
                'complete' => $this->complete,
                'created_at' => $this->created_at->format('d.m.Y'),
                'chanels' => $this->chanels->map(function($item){
                    return $item->id;
                }),
                'trash' => $this->deleted_at ? 1 : 0,
                'deleted_at' => $this->deleted_at,
        ];
    }
}
