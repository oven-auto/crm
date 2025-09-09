<?php

namespace App\Classes\Car\CarPriority;

class ToxicState extends AbstractState implements PriorityStateInterface
{
    private const PRIORITY_ID = 6;

    private static $status;
    
    public static function check(PrioritySetter $context, string $status) : void
    {
        $now = now()->subDays(180);

        static::$status = $status;

        if($context->hasRansom())
            if($now > $context->car->stockDate())
                $context->appendState(new self);
    }



    public function getPriority() : int
    {
        return static::$status;
    }
}