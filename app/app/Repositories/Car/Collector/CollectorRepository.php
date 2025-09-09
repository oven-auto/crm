<?php

namespace App\Repositories\Car\Collector;

use App\Http\DTO\Car\Collector\CreateCarCollectorDTO;
use App\Models\Collector;
use Illuminate\Database\Eloquent\Collection;

Class CollectorRepository
{
    public function get(array $data) : Collection
    {
        $query = Collector::query();

        if (isset($data['trash']))
            $query->onlyTrashed();

        $collectors = $query->get();

        return $collectors;
    }



    public function getById(int $id, string $only = '') : Collector
    {
        $query = Collector::query();
        
        match($only){
            'trash' => $query->onlyTrashed(),
            default => '',
        };

        $collector = $query->findOrFail($id);

        return $collector;
    }



    public function create(CreateCarCollectorDTO $dto) : Collector
    {
        $collector = Collector::create($dto->getAsArray());

        return $collector;
    }



    public function update(int $id, CreateCarCollectorDTO $dto) : Collector
    {
        $collector = $this->getById($id);

        $collector->fill($dto->getAsArray());

        if($collector->isDirty())
            $collector->save();

        return $collector;
    }



    public function delete(int $id) : void
    {
        Collector::where('id', $id)->delete();

        return;
    }



    public function restore(int $id) :void
    {
        $collector = $this->getById(id: $id, only: 'trash');

        $collector->restore();

        return;
    }
}