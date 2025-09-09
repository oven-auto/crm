<?php

namespace App\Classes\Car\CarPriority;

class ProblemEntryState extends AbstractState implements PriorityStateInterface
{
    private const PRIORITY_ID = 5;

    private static $status;

    public static function check(PrioritySetter $context, string $status) : void
    {
        static::$status = $status;

        $now = now()->subDays(180);

        if($context->hasRansom())
            if($now <= $context->car->stockDate())
                $context->appendState(new self);
    }



    public function getPriority() : int
    {
        return static::$status;
    }
}