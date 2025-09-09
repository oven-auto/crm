<?php

namespace App\Http\Resources\Worksheet\Service;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentServiceItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => new CommentServiceResource($this),
            'success' => 1,
        ];
    }
}
