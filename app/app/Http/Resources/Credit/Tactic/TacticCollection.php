<?php

namespace App\Http\Resources\Credit\Tactic;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TacticCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'success' => 1
        ];
    }
}
