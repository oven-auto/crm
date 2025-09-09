<?php

namespace App\Classes\Car\CarPriority;

abstract class AbstractState
{
    public const WEIGHT = 0;

    public function getWeight() : int
    {
        return static::WEIGHT ?? 0;
    }
}