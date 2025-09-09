<?php

namespace App\Http\Resources\Client;

use App\Http\Resources\Client\Event\Reporter\ClientEventReporterResource;
use Illuminate\Http\Resources\Json\JsonResource;

class EventIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {   
        $author = auth()->user();

        //auth()->user()->id == $this->event->author_id
        $canIChange = (
            (
                auth()->user()->role->permissions->contains('slug', 'cevent_show')
                && $this->executors->contains('id', auth()->user()->id)
            )
            || auth()->user()->role->permissions->contains('slug', 'cevent_show_alien')
            || auth()->user()->id == $this->event->author_id
        ) ? 1 : 0;

        $event = $this->event;

        $executors = $this->executors->filter(function($item) use ($event){
            return ($event->author_id != $item->id);
        });
        $executors->prepend($event->author);

        //Проверка на то что показывать, если выбрано "я ответственный"
        $isIExecutor = (request()->has('executor_ids') && in_array($author->id, request('executor_ids'))) ? 1 : 0;

        return [
            'created_at' => $this->created_at->format('d.m.Y (H:i)'),
            'begin_time' => $this->begin,
            'end_time' => $this->end,
            'id' => $this->id,
            'event_id' => $this->event->id,
            'title' => $this->event->title,
            'date_at' => $this->date_at->format('d.m.Y'),
            'executors' => $executors->map(function($item) use ($event){
                    return [
                        'type' => ($event->author_id != $item->id) ? 'Участник' : 'Автор',
                        'id' => $item->id,
                        'name' => $item->cut_name,
                    ];
            }),
            'type' => $this->event->type->name,
            'client' => $this->event->client->full_name,
            'status' => $this->description->name,
            'working' => $this->confirm == 'waiting' ? 1 : 0,
            'processed_at' => $this->processed_at ? $this->processed_at->format('d.m.Y (H:i)') : '',
            'completer' => $this->completer->cut_name,
            'client_type' => $this->event->client->type->name,
            'comment' => $this->lastcomment->created_at ? $this->lastcomment->created_at->format('d.m.Y (H:i)').' '.$this->lastcomment->text : '',
            'author' => $this->event->author->cut_name,
            'executor' => $isIExecutor ? $author->cut_name : $this->event->executor,
            'trafic' => $this->trafic->id ? $this->trafic->id : '',
            'client_id' => $this->event->client->id,
            'worksheet_id' => $this->trafic->worksheet->id,
            'group' => $this->event->group->name ?? '',
            'can_i_change' => $canIChange,

            'trafic_count' => $this->trafics->count(),
            'trafic_list' => $this->trafics->map(function($item)
                {
                    return [
                        'id' => $item->id,
                        'date_at' => $item->created_at->format('d.m.Y (H:i)'),
                        'author' => $item->author->cut_name,

                    ];
                }),
            'reporters_count' => $this->reporters->count(),
            'reporters' => ClientEventReporterResource::collection($this->reporters),
            'me_in_reporters' => ($this->reporters->contains('id', auth()->user()->id)) ? 1 : 0,
            'personal' => $this->event->personal,

            'links' => $this->links->count(),
            'files' => $this->files->count(),
        ];
    }
}
