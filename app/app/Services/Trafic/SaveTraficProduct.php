<?php

namespace App\Services\Trafic;

use App\Models\Trafic;

Class SaveTraficProduct extends AbstractTraficSaveService
{
    public function action(Trafic $trafic, array $data)
    {
        if (isset($data['trafic_need_id'])) {
            $trafic->saveNeeds()->delete();
            foreach ($data['trafic_need_id'] as $item)
                $trafic->saveNeeds()->create(['trafic_product_number' => $item['id']]);
        }
    }
}