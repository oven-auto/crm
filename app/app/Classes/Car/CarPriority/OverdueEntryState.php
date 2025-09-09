<?php

namespace App\Classes\Car\CarPriority;

use App\Classes\Car\CarPriority\PriorityStateInterface;

Class OverdueEntryState extends AbstractState implements PriorityStateInterface
{
    private const PRIORITY_ID = 4;

    private static $status;

    public static function check(PrioritySetter $context, string $status): void
    {
        static::$status = $status;

        $now = $context->now;

        if(!$context->hasRansom())
            if($context->hasPaidDate() && $context->hasControllPaidDate())
                if($context->car->paid_date->date_at <= $now && $context->car->control_paid_date->date_at <= $now)
                    $context->appendState(new self);
    }



    public function getPriority() : int
    {
        return static::$status;
    }
}