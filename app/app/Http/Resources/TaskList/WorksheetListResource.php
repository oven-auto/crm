<?php

namespace App\Http\Resources\TaskList;

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
            'id'                => $this->id,
            'type'              => $this->type,
            'status'            => $this->status,
            'client'            => $this->client,
            'begin_at'          => $this->begin_at,
            'end_at'            => $this->end_at,
            'appeal'            => $this->appeal,
            'comment'           => $this->comment,
            'author'            => $this->author,
            'managers'          => $this->managers,
            'worksheet_status'  => $this->worksheet_status,
            'salon'             => $this->salon,
            'structure'         => $this->structure,
            'sub_action_id'     => $this->sub_action_id,
            'reporters'         => $this->reporters,
            'closed_at'          => $this->closed_at,
            'sort' => $this->sort,
            'client_id'         => $this->client_id,
        ];
    }
}
