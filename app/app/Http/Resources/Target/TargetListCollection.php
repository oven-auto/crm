<?php

namespace App\Http\Resources\Target;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TargetListCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => collect($this->items())->groupBy(function($item){
                return $item->resource->date_at->format('m.Y');
            }),
            'success' => 1,
        ];
    }
}
