<?php

namespace App\Repositories\UsedCar;

use App\Http\Filters\UsedCarFilter;
use App\Models\UsedCar;

class UsedCarRepository
{
    public function getById(int $id) : UsedCar
    {
        $car = UsedCar::findOrFail($id);

        return $car;
    }



    public function update(int $id, array $data) :UsedCar
    {
        $car = $this->getById($id);

        return $car;
    }



    public function paginate(array $data)
    {
        $query = UsedCar::select('used_cars.*')->fullLazyLoad();

        $queryParams = ['queryParams' => array_filter($data)];

        $filter = app()->make(UsedCarFilter::class, $queryParams);

        $query->filter($filter);

        $cars = $query->simplePaginate();

        return $cars;
    }
}
