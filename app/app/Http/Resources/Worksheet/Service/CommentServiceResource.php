<?php

namespace App\Http\Resources\Worksheet\Service;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'text' => $this->text,
            'id' => $this->id,
            'author' => $this->author->cut_name,
            'created_at' => $this->created_at->format('d.m.Y (H:i)'),
            'type' => $this->type,
        ];
    }
}
