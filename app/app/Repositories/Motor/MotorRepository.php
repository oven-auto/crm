<?php

namespace App\Repositories\Motor;

use App\Models\Motor;
use App\Http\Filters\MotorFilter;

Class MotorRepository
{
    public function getAll($data = [])
    {
        $query = Motor::select(['motors.*'])
            ->fullData();
        $filter = app()->make(MotorFilter::class, ['queryParams' => array_filter($data)]);

        $motors = $query->filter($filter)
            ->orderBy('brand_id')
            ->orderBy('power')
            ->get();
        return $motors;
    }

    public function save(Motor $motor, $data = [])
    {
        $motor->fill($data)->save();
        return $motor;
    }
}
