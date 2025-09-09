<?php

namespace App\Http\Resources\Worksheet\Action;

use Illuminate\Http\Resources\Json\JsonResource;

class ActionSaveResource extends JsonResource
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
                'worksheet_status' => $this->worksheet->status->name,
                'worksheet_id' => $this->worksheet_id,
                'action_id' => $this->id,
                'begin_at' => $this->begin_date,
                'end_at' => $this->end_date,
                'task' => $this->task->name,
                'is_working' => $this->isWorking(),
                'is_waiting' => $this->isWaiting(),
                'status_msg' => $this->statusMsg(),
                'last_comment' => [
                    'created_at' => $this->last_comment->created_at ? $this->last_comment->created_at->format('d.m.Y (H:i)') : '',
                    'text' => $this->last_comment->text,
                    'author' => $this->last_comment->author->cut_name
                ],
            ],
            //'task' => $task,
            'success' => 1,
            'message' => 'Действие создано'
        ];
    }
}
