<?php

namespace App\Http\Resources\Audit;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubQuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'question_id'   => $this->question_id,
            'text'          => $this->text,
            'multiple'      => $this->multiple,
            'id'            => $this->id,
        ];
    }
}
