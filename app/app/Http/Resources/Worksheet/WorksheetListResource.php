<?php

namespace App\Http\Resources\Worksheet;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Client\Event\Reporter\ClientEventReporterResource;

class WorksheetListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $class = null;
        $now = date('y-m-d H:i:s');

        if($this->last_action->end_at && $this->isWork())
        {
            if(date('y-m-d') == $this->last_action->end_at->format('y-m-d') && $now < $this->last_action->end_at->format('y-m-d H:i'))
                $class = 'green-bg';
            if($now > $this->last_action->end_at->format('y-m-d H:i:s'))
                $class = 'warning-bg';
        }

        $executors = $this->executors;
        if(!$this->executors->contains('id', $this->author_id))
            $executors->prepend($this->author);

        $executors = $executors->map(function($item){
                return [
                    'type' => $item->id == $this->author_id ? 'Автор' : 'Участник',
                    'name' => $item->cut_name,
                    'id' => $item->id,
                ];
            })->toArray();

        uasort($executors, function($a, $b) {
            return $a['type'] > $b['type'];
        });

        return [
            'created_at' => $this->created_at->format('d.m.Y (H:i)'),
            'id' => $this->id,
            'salon' => $this->company->name,
            'structure' => $this->structure->name,
            'appeal' => $this->appeal->name,
            'author' => $this->author->cut_name,
            'executors' => $executors,
            'status' => $this->status->name,
            'client' => [
                'type' => $this->client->type->name,
                'name' => $this->client->full_name,
            ],
            'action' => $this->last_action->task->name,
            'action_date' => $this->last_action->begin_at ? $this->last_action->begin_at->format('d.m.Y (H:i)') : null,
            'check_marker' => [
                'author' => $this->inspector->cut_name,
                'check_date' => $this->close_at ? $this->close_at->format('d.m.Y (H:i)') : '',
            ],
            'row_color' => $class,
            'can_i_change' => 1,
            'reporters_count' => $this->reporters->count(),
            'reporters' => ClientEventReporterResource::collection($this->reporters),
            'me_in_reporters' => ($this->reporters->contains('id', auth()->user()->id)) ? 1 : 0,
        ];
    }
}
