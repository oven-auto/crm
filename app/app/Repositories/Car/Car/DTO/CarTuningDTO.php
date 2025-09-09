<?php

namespace App\Repositories\Car\Car\DTO;

use App\Classes\DTO\AbstractDTO;

class CarTuningDTO extends AbstractDTO
{
    private const FIELDS = [ 'devices'];

    public function __construct(array $data)
    {
        $this->fields = self::FIELDS;

        foreach($this->fields as $item)
            if(array_key_exists($item, $data))
                $this->set($item, $data[$item]);
    }
}
