<?php

namespace App\Http\Resources\Client\Event\File;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientEventFileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $arr = explode('/',$this->file);

        return [
            'tet' => file_exists(public_path('storage/'.$this->file)),
            'file' => \WebUrl::make_link($this->file),
            'author' => $this->author->cut_name,
            'created_at' =>$this->created_at->format('d.m.Y (H:i)'),
            'name' => end($arr),
            'id' => $this->id
        ];
    }
}
