<?php 

namespace App\Repositories\Worksheet\Modules\Credit;

use App\Helpers\Array\ArrayHelper;
use App\Http\DTO\Worksheet\Credit\CreateCreditDTO;
use App\Http\Filters\CreditFilter;
use App\Models\WSMCredit;
use App\Models\WSMCreditCar;
use Exception;
use Illuminate\Support\Facades\DB;

Class CreditRepository
{
    public function createAward(WSMCredit $credit, CreateCreditDTO $dto)
    {
        $data = ArrayHelper::getOnlyNotNullable((array) $dto->award);

        if(count($data))
            $credit->award()->updateOrCreate(
                ['wsm_credit_id' => $credit->id],
                ArrayHelper::getOnlyNotNullable((array) $dto->award)
            );
        elseif($credit->award)
            $credit->award->delete();
    }



    public function createContract(WSMCredit $credit, CreateCreditDTO $dto)
    {
        $data = ArrayHelper::getOnlyNotNullable((array) $dto->contract);
        
        if(count($data))
            $credit->contract()->updateOrCreate(
                ['wsm_credit_id' => $credit->id],
                ArrayHelper::getOnlyNotNullable((array) $dto->contract)
            );
        elseif($credit->contract)
            $credit->contract->delete();
    }




    public function createDeduction(WSMCredit $credit, CreateCreditDTO $dto)
    {
        $data = ArrayHelper::getOnlyNotNullable((array) $dto->deduction);

        if(count($data))
            $credit->deduction()->updateOrCreate(
                ['wsm_credit_id' => $credit->id],
                ArrayHelper::getOnlyNotNullable((array) $dto->deduction)
            );
        elseif($credit->deduction)
            $credit->deduction->delete();
    }



    public function createCalculation(WSMCredit $credit, CreateCreditDTO $dto)
    {
        $data = ArrayHelper::getOnlyNotNullable((array) $dto->calculation);

        if(count($data))
            $credit->calculation()->updateOrCreate(
                ['wsm_credit_id' => $credit->id],
                ArrayHelper::getOnlyNotNullable((array) $dto->calculation)
            );
        elseif($credit->calculation)
            $credit->calculation->delete();
    }



    public function createServices(WSMCredit $credit, CreateCreditDTO $dto)
    {
        $credit->services()->sync((array) $dto->services->services);
    }



    public function createApproximates(WSMCredit $credit, CreateCreditDTO $dto)
    {
        $credit->approximates()->sync($dto->approximates->approximates);
    }



    public function createCar(WSMCredit $credit, CreateCreditDTO $dto)
    {
        $type = WSMCreditCar::getModelName($dto->car->type);

        $car = $type::findOrFail($dto->car->id);

        if($car) 
        {
            $credit->car()->updateOrCreate(
                ['wsm_credit_id' => $credit->id],
                ['carable_id' => $car->id, 'carable_type' => $type, 'vin' => $car->vin]
            );
        }
    }



    public function createContent(WSMCredit $credit, CreateCreditDTO $dto)
    {
        $credit->content()->sync($dto->content);
    }



    public function getById(int $id)
    {
        return WSMCredit::findOrFail($id);
    }



    private function save(WSMCredit $credit, CreateCreditDTO $dto)
    {
        $result = DB::transaction(function() use ($credit, $dto){
            $credit->fill((array) $dto->credit)->save();

                $this->createAward($credit, $dto);

                $this->createContract($credit, $dto);

                $this->createDeduction($credit, $dto);

                $this->createCalculation($credit, $dto);

                $this->createServices($credit, $dto);

                $this->createContent($credit, $dto);

            if(!ArrayHelper::isAllNull((array) $dto->car))
                $this->createCar($credit, $dto);

            $credit->refresh();
            
            return $credit;
        }, 3);

        return $result;
    }



    /**
     * СОЗДАТЬ КРЕДИТНЫЙ МОДУЛЬ
     * @param CreateCreditDTO $dto данные
     * @return WSMCredit
     */
    public function create(CreateCreditDTO $dto) : WSMCredit
    {
        $credit = new WSMCredit();

        $result = $this->save($credit, $dto);

        return $result;
    }



    /**
     * ИЗМЕНИТЬ КРЕДИТНЫЙ МОДУЛЬ
     * @param int $id идентификатор модуля
     * @param CreateCreditDTO $dto данные
     * @return WSMCredit
     */
    public function update(int $id, CreateCreditDTO $dto) : WSMCredit
    {
        $credit = $this->getById($id);

        $result = $this->save($credit, $dto);
        
        return $result;
    }



    public function getToWorksheet(array $data)
    {
        if(!isset($data['worksheet_id']))
            throw new Exception("Не указан РЛ.");

        $query = WSMCredit::query()->select('wsm_credits.*')->allRelations();

        $query->where('worksheet_id', $data['worksheet_id']);

        $result = $query->get();

        return $result;
    }



    public function delete(int $id) : void
    {
        $credit = $this->getById($id);

        $credit->delete();
    }



    public function paginate(array $data)
    {
        $query = WSMCredit::query()->allRelations()->select('wsm_credits.*');

        $filter = app()->make(CreditFilter::class, ['queryParams' => ($data)]);

        $query->filter($filter);

        $result = $query->simplePaginate(20);

        return $result;
    }



    public function count(array $data)
    {
        $query = WSMCredit::select([
            DB::raw('wsm_credits.id'),
            DB::raw('COUNT(wsm_credits.id) as _count'),
            DB::raw('IFNULL(wsm_credit_calculations.cost, 0) as _cost_credit'),
            DB::raw('IFNULL(wsm_credit_awards.sum, 0) as _award'),
            DB::raw('IFNULL(wsm_credit_deductions.sum, 0) as _deduction'),
            DB::raw('SUM(IFNULL(wsm_services.cost,0)) as _cost_service'),
        ]);

        $filter = app()->make(CreditFilter::class, ['queryParams' => ($data)]);

        $query->filter($filter);

        $res = DB::table($query)
            ->select(
                DB::raw('CAST(COUNT(_count) as INT) as count'),
                DB::raw('CAST(SUM(_cost_credit) as INT) as cost_credit'),
                DB::raw('CAST(SUM(_award) as INT) as award'),
                DB::raw('CAST(SUM(_deduction) as INT) as deduction'),
                DB::raw('CAST(SUM(_cost_service) as INT) as cost_service'),
            )
            ->first();

        return $res;
    }



    public function get(array $data)
    {
        $query = WSMCredit::query()->allRelations()->select('wsm_credits.*');

        $filter = app()->make(CreditFilter::class, ['queryParams' => ($data)]);

        $query->filter($filter);

        $result = $query->get(1000);

        return $result;
    }
}