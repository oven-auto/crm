<?php

namespace App\Repositories\Worksheet\DTO;

use App\Classes\DTO\AbstractDTO;
use App\Models\Worksheet;

class RedemptionCreateDTO extends AbstractDTO
{
    public function __construct(Worksheet $worksheet, array $obj)
    {
        $obj = (object) $obj;

        $this->data = [
            'client_car_id' => $obj->client_car_id,
            'worksheet_id' => $worksheet->id,
            'car_sale_sign_id' => $obj->car_sale_sign_id,
            'author_id' => auth()->user()->id,
            'client_id' => $worksheet->client_id,
            'redemption_status_id' => 1,
            'expectation' => $obj->expectation ?? 0,
            'redemption_type_id' => $obj->redemption_type_id,
        ];
    }
}
