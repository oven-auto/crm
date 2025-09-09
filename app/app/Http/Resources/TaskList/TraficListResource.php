<?php

namespace App\Http\Resources\TaskList;

use Illuminate\Http\Resources\Json\JsonResource;

class TraficListResource extends JsonResource
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
            'id'            => $this->id,
            'name'          => $this->clientName ? $this->clientName : $this->person->name,
            'status'        => $this->status->description,
            'created_at'    => $this->created_at ? $this->created_at->format('d.m.Y (H:i)') : '',
            'begin_at'      => $this->begin_at ? $this->begin_at->format('d.m.Y (H:i)') : 'Начало контроля не указано',
            'end_at'        => $this->end_at ? $this->end_at->format('d.m.Y (H:i)') : 'Конец контроля не указан',
            'interval'      => $this->interval,
            'chanel'        => $this->chanel->name,
            'parent_chanel' => $this->chanel->myparent->name,
            'author'        => $this->author->cut_name,
            'manager'       => $this->manager->cut_name,
            'salon'         => $this->salon->name,
            'structure'     => isset($this->structure) ? $this->structure->name : '',
            'appeal'        => $this->appeal ? $this->appeal->name : 'Цель обращения неизвестна',
            'needs'         => $this->needs ? $this->needs->map(function($need){
                                return $need->name;
                            }) : ['Товары\услуги не выбраны'],
            'processing_at' => $this->processing_at ? $this->processing_at->format('d.m.Y (H:i)') : '',
            'comment'       => $this->comment,
            'closed_at'     => $this->deleted_at ? $this->deleted_at->format('d.m.Y (H:i)') : ($this->processing_at ? $this->processing_at->format('d.m.Y (H:i)') : ''),
        ];
    }
}
