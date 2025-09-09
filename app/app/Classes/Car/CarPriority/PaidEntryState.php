<?php

namespace App\Classes\Car\CarPriority;

Class PaidEntryState extends AbstractState implements PriorityStateInterface
{
    private const PRIORITY_ID = 3;

    private static $status;

    public static function check(PrioritySetter $context, string $status): void
    {
        static::$status = $status;

        $now = $context->now;

        if(!$context->hasRansom())
            if($context->hasPaidDate() && $context->hasControllPaidDate())
                if($context->car->paid_date->date_at <= $now && $context->car->control_paid_date->date_at >= $now)
                    $context->appendState(new self);
    }



    public function getPriority() : int
    {
        return static::$status;
    }
}