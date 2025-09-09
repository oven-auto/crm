<?php

namespace App\Http\Resources\Worksheet;

use Illuminate\Http\Resources\Json\JsonResource;

class WorksheetCreateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => [
                'worksheet' => $this->attributesToArray(),
                'trafic' => ['status' => $this->trafic->status->attributesToArray()]
            ],
            'success' => 1
        ];
    }
}
