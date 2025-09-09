<?php

namespace App\Repositories\Car\Marker\DTO;

use App\Classes\DTO\AbstractDTO;

Class MarkerDTO extends AbstractDTO
{
    private const FIELDS = ['name', 'text_color', 'body_color', 'description'];

    public function __construct(array $data)
    {
        $this->fields = self::FIELDS;

        foreach($this->fields as $item)
            if(array_key_exists($item, $data))
                $this->set($item, $data[$item]);
    }
}
