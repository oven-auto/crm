<?php

namespace App\Http\Resources\Trafic;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TraficSexCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request, $str='')
    {
        return [
            'data' => $this->collection,
            'success' => $this->collection->count() ? 1 : 0,
            'message' =>'Найдено '.$this->collection->count().' элементов',
            'count' => $this->collection->count(),
        ];
    }
}
