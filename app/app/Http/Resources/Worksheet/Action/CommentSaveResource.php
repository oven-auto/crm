<?php

namespace App\Http\Resources\Worksheet\Action;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentSaveResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => [
                'created_at' => $this->created_at->format('d.m.Y (H:i)'),
                'text' => $this->text,
                'author' => $this->author->cut_name
            ],
            'success' => 1,
            'message' => 'Комментарий добавлен'
        ];
    }
}
