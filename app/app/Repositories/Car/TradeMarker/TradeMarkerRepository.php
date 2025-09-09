<?php

namespace App\Repositories\Car\TradeMarker;

use App\Models\TradeMarker;

class TradeMarkerRepository
{
    public function get(array $data)
    {
        $query = TradeMarker::query();

        if (isset($data['trash']))
            $query->onlyTrashed();

        $result = $query->get();

        return $result;
    }



    public function save(TradeMarker $marker, array $data)
    {
        $marker->fill($data)->save();
    }



    public function delete(TradeMarker $marker)
    {
        $marker->delete();
    }



    public function restore(TradeMarker $marker)
    {
        $marker->restore();
    }
}
