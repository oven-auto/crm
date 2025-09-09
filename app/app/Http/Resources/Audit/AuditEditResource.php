<?php

namespace App\Http\Resources\Audit;

use App\Http\Resources\User\UserSmallResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditEditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'data' => [
                'id' => $this->id,
                'name' => $this->name,
                'author' => new UserSmallResource($this->author),
                'editor' => new UserSmallResource($this->editor),
                'appeal' => $this->appeal->id,
                'bonus' => $this->bonus,
                'malus' => $this->malus,
                'complete' => $this->complete,
                'created_at' => $this->created_at->format('d.m.Y (H:i)'),
                'chanels' => $this->chanels->map(function($item){
                    return $item->id;
                }),
                'trash' => $this->deleted_at ? 1 : 0,
                'deleted_at' => $this->deleted_at,
                'updated_at' => $this->updated_at->format('d.m.Y (H:i)'),
                'award' => $this->award,
            ],
            'success' => 1,
        ];
    }
}
