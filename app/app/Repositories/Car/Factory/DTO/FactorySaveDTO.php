<?php

namespace App\Repositories\Car\Factory\DTO;

use App\Classes\DTO\AbstractDTO;

class FactorySaveDTO extends AbstractDTO
{
    private const FIELDS = ['city', 'country'];

    public function __construct(array $data)
    {
        $this->fields = self::FIELDS;

        foreach ($this->fields as $item)
            if (array_key_exists($item, $data))
                $this->set($item, $data[$item]);
    }
}
