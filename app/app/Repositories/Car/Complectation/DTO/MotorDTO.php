<?php

namespace App\Repositories\Car\Complectation\DTO;

use App\Classes\DTO\AbstractDTO;

Class MotorDTO extends AbstractDTO
{
    private const FIELDS = ['motor_transmission_id', 'motor_driver_id', 'motor_type_id', 'power', 'size', 'brand_id'];

    public function __construct(array $data)
    {
        $this->fields = self::FIELDS;

        foreach(self::FIELDS as $item)
            if(array_key_exists($item, $data))
                $this->set($item, $data[$item]);
    }
}
