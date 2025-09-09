<?php

namespace App\Http\Resources\Client\Event\Reporter;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientEventReporterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if(isset($this->created_at))
            $date = $this->created_at->format('d.m.Y (H:i)');
        elseif(isset($this->pivot) && $this->pivot->created_at)
            $date = $this->pivot->created_at->format('d.m.Y (H:i)');
        else
            $date = '';

        return [
            'id' => $this->id,
            'name' =>$this->cut_name,
            'created_at' => $date
        ];
    }
}
