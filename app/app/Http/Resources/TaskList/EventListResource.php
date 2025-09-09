<?php

namespace App\Http\Resources\TaskList;

use Illuminate\Http\Resources\Json\JsonResource;

class EventListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $executors = $this->executors->filter(function($item) {
            if($this->event->author_id != $item->id)
                return $item->cut_name;
        });

        return [
            'id'            => $this->id,
            'client'        => $this->event->client->full_name,
            'status'        => $this->status,
            'date_at'       => $this->date_at->format('d.m.Y (H:i)'),
            'comment'       => $this->lastComment->text,
            'begin_time'    => $this->date_at->format('d.m.Y').' ('.$this->begin.')',
            'end_time'      => $this->date_at->format('d.m.Y').' ('.$this->end.')',
            'type'          => $this->event->type->name,
            'group'         => $this->event->group->name,
            'title'         => $this->event->title,
            'author'        => $this->event->author->cut_name,
            'executors'     => $executors->map(function($item){
                return $item->cut_name;
            })->toArray(),
            'reporters'     => $this->reporters->map(function($item) {
                return $item->cut_name;
            }),
            'personality'   => $this->event->personality(),
            'closed_at'     => $this->processed_at ? $this->processed_at->format('d.m.Y (H:i)') : '',
        ];
    }
}
