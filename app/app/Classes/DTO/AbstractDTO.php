<?php

namespace App\Classes\DTO;

abstract class AbstractDTO
{
    protected $data;

    protected $fields = [];

    public function set($key, $val)
    {
        if (in_array($key, $this->fields))
            $this->data[$key] = $val;
    }



    public function get($key = null)
    {
        if ($key && isset($this->data[$key]))
            return $this->data[$key];

        return $this->data ?? [];
    }
}
