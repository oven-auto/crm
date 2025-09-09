<?php

namespace App\Http\Resources\Client;

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
        // $canIChange = (
        //     (
        //         auth()->user()->role->permissions->contains('slug', 'cevent_show')
        //         && $this->event->executors->contains('id', auth()->user()->id)
        //     )
        //     || auth()->user()->role->permissions->contains('slug', 'cevent_show_alien')
        // ) ? 1 : 0;

        return [
            'id' => $this->id,
            'title' => $this->event->title ?? '',
            'text' => $this->lastComment->text,
            'author' => $this->event->author->cut_name,
            'type' => $this->event->type->name,
            'date_at' => $this->date_at->format('d.m.Y'),
            'status' => $this->status,
            'property' => $this->event->group->name,
            'can_i_change' => 1,//$canIChange,
        ];
    }
}
