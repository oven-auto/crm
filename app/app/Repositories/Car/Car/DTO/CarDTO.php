<?php

namespace App\Repositories\Car\Car\DTO;

use App\Classes\DTO\AbstractDTO;

class CarDTO extends AbstractDTO
{
    private const FIELDS = ['mark_id', 'brand_id', 'complectation_id', 'color_id', 'year', 'vin', 'disable_sale', 'disable_off'];

    private const SOMETIMES = ['vin', 'disable_sale', 'disable_off'];

    public function __construct(array $data)
    {
        $this->fields = self::FIELDS;

        foreach ($this->fields as $item)
            if (array_key_exists($item, $data))
                $this->set($item, $data[$item]);

        if (!array_key_exists('vin', $data))
            $this->set('vin', '');
    }
}
