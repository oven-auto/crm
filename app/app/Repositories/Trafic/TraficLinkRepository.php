<?php

namespace App\Repositories\Trafic;

use App\Models\Trafic;
use App\Models\TraficLink;
use App\Services\Trafic\SaveTraficLink;

Class TraficLinkRepository
{
    public function get(int|Trafic $trafic)
    {
        if(is_numeric($trafic))
            $trafic = Trafic::find($trafic);

        return $trafic->links;
    }



    public function createTraficLink(Trafic $trafic, array $data) : TraficLink|null
    {
        SaveTraficLink::save($trafic, $data);

        return $trafic->links->last();
    }



    public function delete(TraficLink $link)
    {
        $link->delete();
    }



    public function count(int $traficId)
    {
        $count = TraficLink::where('trafic_id', $traficId)->count();

        return $count;
    }
}