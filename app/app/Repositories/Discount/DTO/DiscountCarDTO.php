<?php

namespace App\Repositories\Discount\DTO;

use App\Classes\DTO\AbstractDTO;

class DiscountCarDTO extends AbstractDTO
{
    private const FIELDS = ['name', 'returnable',  'salon_id', 'modul_id'];

    public function __construct(array $data)
    {
        $this->fields = self::FIELDS;

        $this->set('name', $data['name']);
        $this->set('returnable', $data['returnable']);
        $this->set('salon_id', $data['salon']);
        $this->set('modul_id', $data['modul']);
    }
}
