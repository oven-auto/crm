<?php

namespace App\Http\Resources\Client\ClientEvent;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientEventTemplateResource extends JsonResource
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
            'title' => $this->title,
            'group' => $this->group_id,
            'type' => $this->type_id,
            'comment' => $this->comment,
            'executors' => $this->getExecutors(),
            'begin' => $this->begin,
            'trash' => $this->deleted_at ? 1  : 0,
            'author' => $this->author_id,
            'status' => (int)$this->status,
            'resolve' => (int) $this->resolve,
            'process' => $this->process ?? [],
            'links' => json_decode($this->links),
        ];
    }
}
