<?php

namespace App\Classes\Car\CarPriority;

interface PriorityStateInterface
{
    public static function check(PrioritySetter $context, string $status) : void;

    public function getWeight() : int;

    public function getPriority() : int;
}