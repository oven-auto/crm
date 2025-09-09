<?php

namespace App\Repositories\Car\Factory;

use App\Models\Factory;
use App\Repositories\Car\Factory\DTO\FactorySaveDTO;

class FactoryRepository
{
    public function get(array $data = [])
    {
        $query = Factory::query();

        if (isset($data['trash']) && $data['trash'] > 0)
            $query->onlyTrashed();

        $factories = $query->get();

        return $factories;
    }



    public function save(Factory $factory, array $data)
    {
        $factory->fill((new FactorySaveDTO($data))->get())->save();
    }



    public function delete(Factory $factory)
    {
        $factory->delete();
    }



    public function restore(Factory $factory)
    {
        $factory->restore();
    }
}
