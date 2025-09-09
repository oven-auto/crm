<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class MotorFilter extends AbstractFilter
{
    public const BRAND_ID = 'brand_id';
    public const NAME = 'name';
    public const MOTOR_TRANSMISSION_ID = 'motor_transmission_id';
    public const MOTOR_DRIVER_ID = 'motor_driver_id';
    public const MOTOR_TYPE_ID = 'motor_type_id';
    public const MOTOR_TOXIC_ID = 'motor_toxic_id';

    protected function getCallbacks(): array
    {
        return [
            self::BRAND_ID          => [$this, 'brandId'],
            self::NAME              => [$this, 'name'],
            self::MOTOR_TRANSMISSION_ID => [$this, 'motor_transmission_id'],
            self::MOTOR_DRIVER_ID => [$this, 'motor_driver_id'],
            self::MOTOR_TYPE_ID => [$this, 'motor_type_id'],
            self::MOTOR_TOXIC_ID => [$this, 'motor_toxic_id'],
        ];
    }

    public function brandId(Builder $builder, $value)
    {
        $builder->where('motors.brand_id', $value);
    }

    public function name(Builder $builder, $value)
    {
        $builder->where('motors.name', 'like', "%{$value}%");
    }

    public function motor_transmission_id(Builder $builder, $value)
    {
        $builder->where('motors.motor_transmission_id', $value);
    }

    public function motor_driver_id(Builder $builder, $value)
    {
        $builder->where('motors.motor_driver_id', $value);
    }

    public function motor_type_id(Builder $builder, $value)
    {
        $builder->where('motors.motor_type_id', $value);
    }

    public function motor_toxic_id(Builder $builder, $value)
    {
        $builder->where('motors.motor_toxic_id', $value);
    }
}
