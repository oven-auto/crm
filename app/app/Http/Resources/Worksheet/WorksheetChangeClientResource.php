<?php

namespace App\Http\Resources\Worksheet;

use Illuminate\Http\Resources\Json\JsonResource;

class WorksheetChangeClientResource extends JsonResource
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
            ],
            'success' => 1,
            'message' => 'Клиент изменен'
        ];
    }
}
