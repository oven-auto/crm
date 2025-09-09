<?php

namespace App\Repositories\Car\Option\DTO;

use App\Classes\DTO\AbstractDTO;

class OptionDTO extends AbstractDTO
{
    private const FIELDS = ['name', 'code',  'brand_id', 'mark_id'];

    public function __construct(array $data)
    {
        $this->fields = self::FIELDS;

        foreach ($this->fields as $item)
            if (array_key_exists($item, $data))
                $this->set($item, $data[$item]);
    }
}
