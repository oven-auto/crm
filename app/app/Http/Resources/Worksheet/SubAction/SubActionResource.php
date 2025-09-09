<?php

namespace App\Http\Resources\Worksheet\SubAction;

use Illuminate\Http\Resources\Json\JsonResource;

class SubActionResource extends JsonResource
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
                'client'            => [
                    'name' => $this->worksheet->client->full_name,
                    'id' => $this->worksheet->client->id,
                ],
                'indicator'         => $this->indicator(),
                'created_at'        => $this->created_at->format('d.m.Y (H:i').' - '.$this->created_at->addMinutes($this->duration)->format('H:i)'),
                'id'                => $this->id,
                'author'            => [
                    'name' => $this->author->cut_name,
                    'id' => $this->author->id,
                ],
                'worksheet_id'      => $this->worksheet_id,
                'duration'          => $this->duration,
                'title'             => $this->title,
                'status'            => $this->getStatus(),
                'executors'         => $this->executors->except('id', $this->author_id)->map(function($itemUser) {
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
                'comment' => $this->comments->first() ? [
                    'text' => $this->comments->first()->text,
                    'author' => $this->comments->first()->author->cut_name,
                ] : [],
            ],
            'success' => 1,
        ];
    }
}
