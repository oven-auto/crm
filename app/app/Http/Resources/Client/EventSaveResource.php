<?php

namespace App\Http\Resources\Client;

use App\Helpers\Url\WebUrl;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;

class EventSaveResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $params = [
            'date_at' => 1,
            'title' => 1,
            'group_id' => 1,
            'type_id' => 1,
            'text' => 1,
            'executors' => 1,
            'begin_time' => 1,
            'end_time' => 1,
        ];
        if(auth()->user()->id != $this->event->author_id)
            $params = [
                'executors' => 2,
                'text' => 1,
                'date_at' => 0,
                'title' => 0,
                'group_id' => 0,
                'type_id' => 0,
                'begin_time' => 0,
                'end_time' => 0,
            ];

        return [
            'data' => [
                'begin_time' => $this->begin,
                'end_time' => $this->end,
                'time_status' => $this->timeStatus,
                'resolve' => $this->event->resolve,
                'created_at' => $this->event->created_at->format('d.m.Y'),
                'id' => $this->id,
                'client_id' => $this->event->client_id,
                'author_id' => $this->event->author_id,
                'title' => $this->event->title ?? '',
                'group_id' => $this->event->group_id,
                'type_id' => $this->event->type_id,
                'date_at' => $this->date_at ? $this->date_at->format('d.m.Y') : '',
                'client' => $this->event->client->full_name,
                'author' => $this->event->author->cut_name,
                'comments' => $this->event->comments->map(function($item) {
                    return [
                        'created_at' => $item->created_at->format('d.m.Y'),
                        'text' => $item->text,
                        'writer' => $item->author->cut_name,
                    ];
                }),
                'executors' => $this->executors->map(function($item){
                    return $item->id;
                }),
                'personal' => $this->event->personal,
                'status' => $this->confirm,
                'fillable_properties' => $params,
                'method' => Route::current()->methods(),
                'event_status_id' => $this->id,
                'files' => $this->event->files->map(function($itemFile){
                    $arr = explode('/',$itemFile->file);
                    return [
                        'file' => WebUrl::make_link($itemFile->file),
                        'author' => $itemFile->author->cut_name,
                        'created_at' =>$itemFile->created_at->format('d.m.Y (H:i)'),
                        'name' => end($arr),
                        'id' => $itemFile->id
                    ];
                }),
                'reporters' => $this->reporters->map(function($item){
                    return [
                        'id' => $item->id,
                        'name' => $item->cut_name,
                        'created_at' => $item->pivot->created_at ? $item->pivot->created_at->format('d.m.Y (H:i)') : ''
                    ];
                }),
                'file_count' => $this->files->count(),
                'link_count' => $this->links->count(),
            ],
            'success' => 1,
            'event' => new EventIndexResource($this),
        ];
    }
}
