<?php

namespace App\Http\Resources\Client\File;

use Illuminate\Http\Resources\Json\JsonResource;

class SaveResource extends JsonResource
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
                $this->map(function($item){
                    return [
                        'id' => $item->id,
                    ];
                })
            ],
            'success' => 1
        ];
    }
}
