<?php

namespace App\Repositories\Car\Car\DTO;

use App\Classes\DTO\AbstractDTO;
use App\Models\LogisticState;

Class LogisticDateDTO extends AbstractDTO
{
    private const FIELDS = [];

    public function __construct(array $data)
    {
        $this->fields = LogisticState::select('system_name')->pluck('system_name')->toArray();

        foreach($this->fields as $item)
            if(array_key_exists($item, $data))
                $this->set($item, $data[$item]);
    }
}
