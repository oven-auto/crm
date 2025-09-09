<?php

namespace App\Http\Resources\Worksheet\SubAction;

use Illuminate\Http\Resources\Json\JsonResource;

class SubActionListResource extends JsonResource
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
            'indicator'         => $this->indicator(),
            'id'                => $this->id,
            'created_at'        => $this->created_at->format('d.m.Y (H:i').' - '.$this->created_at->addMinutes($this->duration)->format('H:i)'),
            'author'            => $this->author->cut_name,
            'worksheet_id'      => $this->worksheet_id,
            'duration'          => $this->duration,
            'status'            => $this->getStatus(),
            'title'             => $this->title,
            'executors'         => $this->executors->map(function($itemUser) {
                return [
                    'id' => $itemUser->id,
                    'name' => $itemUser->cut_name,
                ];
            }),
            'reporters'         => $this->reporters->map(function($itemReporter) {
                return [
                    'id' => $itemReporter->id,
                    'name' => $itemReporter->cut_name,
                ];
            }),
            'comment' => $this->last_comment ? [
                'text' => $this->last_comment->text,
                'author' => $this->last_comment->author->cut_name,
            ] : [],
        ];
    }
}
