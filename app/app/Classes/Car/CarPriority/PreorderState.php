<?php

namespace App\Classes\Car\CarPriority;

Class PreorderState extends AbstractState implements PriorityStateInterface
{
    public const WEIGHT = 30;

    private const PRIORITY_ID = 1;

    private static $status;

    public static function check(PrioritySetter $context, string $status) : void
    {
        static::$status = $status;

        if($context->hasReserve())
            if($context->isPreorder())
                $context->appendState(new self);
    }



    public function getPriority() : int
    {
        return static::$status;
    }
}