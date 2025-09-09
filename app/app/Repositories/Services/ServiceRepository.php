<?php

namespace App\Repositories\Services;

use App\Http\Filters\ServiceFilter;
use App\Models\Service;
use App\Repositories\Services\DTO\ServiceDTO;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

Class ServiceRepository
{
    public function getAll(array $data)
    {
        $query = Service::query()->with(['author','prolongation','calculation', 'applicabilities', 'category']);

        $filter = app()->make(ServiceFilter::class, ['queryParams' => ($data)]);

        $query->filter($filter);

        $result = $query->get();

        return $result;
    }



    public function getById(int $id) : Service
    {
        return Service::withTrashed()->findOrFail($id);
    }



    private function saveProlongation(Service $service, ServiceDTO $dto) : bool
    {
        $dirty = false;
        
        $service->prolongation()->updateOrCreate(
            ['service_id' => $service->id],
            $dto->prolongationData()
        );
        
        return $dirty;
    }



    private function saveCalculation(Service $service, ServiceDTO $dto) : bool
    {
        $dirty = false;

        if($service->calculation)
        {
            $service->calculation->fill($dto->calculationData());
            if($service->calculation->isDirty())
            {
                $dirty = true;
                $service->calculation->save();
            }
        } else 
        {
            $dirty = true;
            $service->calculation()->create($dto->calculationData());
        }

        return $dirty;
    }



    public function saveApplicability(Service $service, ServiceDTO $dto) : bool
    {
        $service->applicabilities()->delete();

        array_map(function($item) use ($service){
            $service->applicabilities()->create([
                'service_id' => $service->id,
                'applicability' => $item
            ]);
        }, $dto->applicability);

        return 1;
    }



    public function saveProviders(Service $service, ServiceDTO $dto)
    {  
        $service->providers()->sync($dto->providers);

        return 1;
    }



    public function saveOver(Service $service, ServiceDTO $dto) : bool
    {
        $result = true;

        $this->saveApplicability($service, $dto);
        $this->saveCalculation($service, $dto);
        $this->saveProlongation($service, $dto);
        $this->saveProviders($service, $dto);
        
        $service->load(['providers']);

        return $result;
    }



    public function create(ServiceDTO $dto)
    {
        $result = DB::transaction(function() use ($dto){
            $service = Service::create(array_merge($dto->mainData(), ['author_id' => Auth::id()]));

            $this->saveOver($service, $dto);

            return $service;
        }, 1);
        
        return $result;
    }



    public function update(int $id, ServiceDTO $dto)
    {          
        $result = DB::transaction(function() use ($id, $dto){
            $service = $this->getById($id);

            $service->fill(array_merge($dto->mainData(), ['author_id' => Auth::id()]));            

            $dirty = $this->saveOver($service, $dto);

            if($service->isDirty() || $dirty)
                $service->save();

            return $service;
        },1);

        return $result;
    }



    public function delete(int $id)
    {
        $service = $this->getById($id);

        $service->delete();
    }



    public function restore(int $id)
    {
        $service = $this->getById($id);

        $service->restore();

        return $service;
    }
}