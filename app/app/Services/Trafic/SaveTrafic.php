<?php

namespace App\Services\Trafic;

use App\Models\Trafic;
use Illuminate\Support\Facades\Auth;

Class SaveTrafic extends AbstractTraficSaveService
{
    public function action(Trafic $trafic, array $data)
    {
        if(!$trafic->id)
            $trafic->author_id = Auth::id();

        $trafic->fill([
            'trafic_zone_id'        => $data['trafic_zone_id'] ?? $trafic->trafic_zone_id,
            'trafic_chanel_id'      => $data['trafic_chanel_id'] ?? $trafic->trafic_chanel_id,
            'company_id'            => $data['trafic_brand_id'] ?? $trafic->company_id,
            'company_structure_id'  => $data['trafic_section_id'] ?? $trafic->company_structure_id,
            'trafic_appeal_id'      => $data['trafic_appeal_id'] ?? $trafic->trafic_appeal_id,
            'manager_id'            => $data['manager_id'] ?? $trafic->manager_id,
        ]);

        $trafic->save();
    }
}