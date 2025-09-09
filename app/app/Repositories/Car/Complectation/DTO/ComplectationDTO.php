<?php

namespace App\Repositories\Car\Complectation\DTO;

use App\Classes\DTO\AbstractDTO;

class ComplectationDTO extends AbstractDTO
{
    private const FIELDS = ['code', 'name', 'mark_id', 'vehicle_type_id', 'body_work_id', 'factory_id', 'motor_id', 'author_id'];

    public function __construct(array $data)
    {
        $this->fields = self::FIELDS;

        foreach ($this->fields as $item)
            if (array_key_exists($item, $data))
                $this->set($item, $data[$item]);
    }
}
