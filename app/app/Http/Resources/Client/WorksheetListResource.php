<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Resources\Json\JsonResource;

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
        return [
            'id' => $this->id,
            'created_at' => $this->created_at->format('d.m.Y (H:i)'),
            'company' => $this->company->name,
            'structure' => $this->structure->name,
            'appeal' => $this->appeal->name,
            'status' => $this->status->name,
            'client_id' => $this->client_id,
            'trafic_id' => $this->trafic_id,
            'status' => $this->status->name,
            'last_action' => [
                'begin_at' => $this->last_action->begin_date,
                'end_at' => $this->last_action->end_date,
                'author' => $this->last_action->author->cut_name,
                'task' => $this->last_action->task->name,
                'is_working' => $this->last_action->isWorking(),
                'is_waiting' => $this->last_action->isWaiting(),
                'status_msg' => $this->last_action->statusMsg(),
                //'status' => $this->last_action->status->name,
            ]
        ];
    }
}
