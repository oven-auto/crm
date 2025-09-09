<?php

namespace App\Http\Resources\Worksheet;

use Illuminate\Http\Resources\Json\JsonResource;

class WorksheetSaveResource extends JsonResource
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
                'close_at' => $this->closeDate,
                'worksheet_status' => $this->status->name,
                'id' => $this->id,
                'trafic_id' => $this->trafic_id,
                'created_at' => $this->created_at->format('d.m.Y (H:i)'),
                'client' => [
                    'id' => $this->client->id,
                    'name' => $this->client->full_name,
                ],
                'subclient' => $this->subclients->map(function($item){
                    return [
                        'id' => $item->id,
                        'name' => $item->full_name,
                    ];
                }),
                'trafic' => [
                    'salon' => $this->company->name,
                    'structure' => $this->structure->name,
                    'appeal' => $this->appeal->name,
                ],
                'author' => [
                    'id' => $this->author->id,
                    'name' => $this->author->cut_name,
                ],
                'executors' => $this->executors->map(function($item){
                    return [
                        'id' => $item->id,
                        'name' => $item->cut_name,
                    ];
                }),
                'last_action' => [
                    'worksheet_id' => $this->id,
                    'action_id' => $this->last_action->id,
                    'begin_at' => $this->last_action->begin_date,
                    'end_at' => $this->last_action->end_date,
                    'task' => $this->last_action->task->name,
                    'is_working' => $this->last_action->isWorking(),
                    'is_waiting' => $this->last_action->isWaiting(),
                    'status_msg' => $this->last_action->statusMsg(),
                    'last_comment' => [
                        'created_at' => $this->last_action->last_user_comment->created_at ? $this->last_action->last_user_comment->created_at->format('d.m.Y (H:i)') : '',
                        'text' => $this->last_action->last_user_comment->text,
                        'author' => $this->last_action->last_user_comment->author->cut_name
                    ]
                ],
                'links' => $this->hasLinks(),
                'files' => $this->hasFiles(),
                'available_modules' => $this->modul_list(),
                'reporters' => $this->reporters->map(fn($item) => [
                    'id' => $item->id,
                    'name' => $item->cut_name,
                ]),
            ],
            'success' => 1,

        ];
    }
}
