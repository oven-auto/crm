<?php

namespace App\Repositories\Worksheet\Modules\Service;

use App\Helpers\Array\ArrayHelper;
use App\Http\DTO\Worksheet\Service\CreateServiceDTO;
use App\Http\Filters\WorksheetServiceFilter;
use App\Models\Worksheet\Service\WSMService;
use App\Models\Worksheet\Service\WSMServiceCar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

Class ServiceWorksheetRepository
{
    public function getAll(array $data)
    {
        $query = WSMService::query()
            ->select('wsm_services.*')
            ->allRelation();

        $filter = app()->make(WorksheetServiceFilter::class, ['queryParams' => ($data)]);

        $query->filter($filter);

        $result = $query->get();

        return $result;
    }



    public function paginate(array $data)
    {
        $query = WSMService::query()
            ->select('wsm_services.*')
            ->allRelation();
        
        $filter = app()->make(WorksheetServiceFilter::class, ['queryParams' => ($data)]);

        $query->filter($filter);

        $result = $query->simplePaginate(20);

        return $result;
    }



    public function get(array $data)
    {
        $query = WSMService::query()
            ->select('wsm_services.*')
            ->allRelation();
        
        $filter = app()->make(WorksheetServiceFilter::class, ['queryParams' => ($data)]);

        $query->filter($filter);

        $result = $query->limit(1000)->get();

        return $result;
    }



    public function count(array $data) : stdClass
    {
        $query = WSMService::select([
            DB::raw('COUNT(wsm_services.id) as _count'),
            DB::raw('SUM(wsm_services.cost) as _cost'),
            DB::raw('SUM(wsm_service_awards.sum) as _award'),
            DB::raw('SUM(wsm_service_deductions.sum) as _deduction')
        ]);

        $filter = app()->make(WorksheetServiceFilter::class, ['queryParams' => ($data)]);

        $query->filter($filter);

        $res = DB::table($query)->select(
            DB::raw('COUNT(_count) as _count'),
            DB::raw('SUM(_cost) as _cost'),
            DB::raw('SUM(_award) as _award'),
            DB::raw('SUM(_deduction) as _deduction'),
        )->first();
        
        return $res;
    }



    private function createAward(WSMService &$service, CreateServiceDTO $dto) : void
    {
        $data = ArrayHelper::getOnlyNotNullable((array) $dto->award);
        
        if(count($data))
            $service->award()->updateOrCreate(
                ['wsm_service_id' => $service->id],
                ArrayHelper::getOnlyNotNullable((array) $dto->award)
            );
        elseif($service->contract)
            $service->contract->delete();
    }



    public function createContract(WSMService &$service, CreateServiceDTO $dto) : void
    {
        $data = ArrayHelper::getOnlyNotNullable((array) $dto->contract);
        
        if(count($data))
            $service->contract()->updateOrCreate(
                ['wsm_service_id' => $service->id],
                ArrayHelper::getOnlyNotNullable((array) $dto->contract)
            );
        elseif($service->contract)
            $service->contract->delete();
    }



    public function createDeduction(WSMService &$service, CreateServiceDTO $dto) : void
    {
        $data = ArrayHelper::getOnlyNotNullable((array) $dto->deduction);

        if(count($data))
            $service->deduction()->updateOrCreate(
                ['wsm_service_id' => $service->id],
                ArrayHelper::getOnlyNotNullable((array) $dto->deduction)
            );
        elseif($service->deduction)
            $service->deduction->delete();
    }



    public function createCar(WSMService &$service, CreateServiceDTO $dto) : void
    {
        $type = WSMServiceCar::getModelName($dto->car->type);
        
        $car = $type::findOrFail($dto->car->id);

        if($car) 
        {
            $service->car()->updateOrCreate(
                ['wsm_service_id' => $service->id],
                ['carable_id' => $car->id, 'carable_type' => $type, 'vin' => $car->vin]
            );
        }
    }



    public function saveService(WSMService &$service, CreateServiceDTO $dto)
    {   
        $service->fill((array) $dto->service);

        $service->save();
    }



    public function save(WSMService $service, CreateServiceDTO $dto)
    {
        $result = DB::transaction(function() use($dto, $service) {
            $this->saveService($service, $dto);

            $this->createAward($service, $dto);

            $this->createContract($service, $dto);

            $this->createDeduction($service, $dto);

            $this->createCar($service, $dto);

            $service->refresh();

            return $service;
        }, 1);

        return $result;
    }



    public function create(CreateServiceDTO $dto) : WSMService
    {
        $service = app()->make(WSMService::class);

        $service->fill(['author_id' => Auth::id()]);

        $this->save($service, $dto);

        return $service;
    }



    public function update(int $id, CreateServiceDTO $dto) : WSMService
    {
        $service = $this->getById($id);

        $this->save($service, $dto);

        return $service;
    }



    public function getById(int $id) : WSMService
    {
        return WSMService::findOrFail($id);
    }



    public function delete(int $id) : void
    {
        $service = $this->getById($id);

        $service->delete();
    }
}