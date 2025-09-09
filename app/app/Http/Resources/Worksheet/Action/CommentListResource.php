<?php

namespace App\Http\Resources\Worksheet\Action;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $i = 0;
        return [
            'data' => $this->map(function($item) use($i){
                return [
                    'created_at' => $item->created_at->format('d.m.Y (H:i)'),
                    'text' => $item->text,
                    'writer' => $item->writer,
                    'status' => $item->status,
                    'type' => $item->type,
                    'author_id' => $item->author_id,
                    'context' => $item->context,
                    'color' => $item->color
                ];
            }),
            'success' => 1
        ];
    }
}
