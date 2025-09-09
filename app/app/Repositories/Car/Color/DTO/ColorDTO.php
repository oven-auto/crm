<?php

namespace App\Repositories\Car\Color\DTO;

use App\Classes\DTO\AbstractDTO;

Class ColorDTO extends AbstractDTO
{
    private const FIELDS = ['name', 'mark_id', 'brand_id', 'base_id'];

    public function __construct(array $data)
    {
        $this->fields = self::FIELDS;

        foreach($this->fields as $item)
            if(array_key_exists($item, $data))
                $this->set($item, $data[$item]);
    }
}
