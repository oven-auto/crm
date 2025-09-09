<?php

namespace App\Repositories\Audit;

use App\Helpers\Number\NumberHelper;
use App\Http\Filters\AuditMasterFilter;
use App\Models\Audit\Audit;
use App\Models\Audit\AuditMaster;
use App\Models\Audit\AuditQuestion;
use App\Repositories\Audit\DTO\AuditMasterDTO;
use App\Repositories\Audit\Services\CalcPoint;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

Class AuditMasterRepository
{
    public function arbitr(int $id)
    {
        $audit = $this->getById($id);

        $audit->arbitrSatus();
    }



    public function delete(int $id)
    {
        $audit = $this->getById($id);        

        $audit->delete();

        return 1;
    }



    public function restore(int $id)
    {
        $audit = $this->getById($id);

        $audit->restore();

        return 1;
    }



    public function getById(int $id) : AuditMaster
    {   
        return AuditMaster::withTrashed()->findOrFail($id);
    }



    public function isExist(array $data) : bool
    {
        $existed = $this->getAll(Arr::only($data, ['trafic_id', 'audit_id']));
        
        if($existed->count())
            return 1;
        return 0;
    }



    public function getAll(array $data)
    {
        $query = AuditMaster::query();

        if(isset($data['trafic_id']))
            $query->where('trafic_id', $data['trafic_id']);

        if(isset($data['audit_id']))
            $query->where('audit_id',  $data['audit_id']);

        $result = $query->withTrashed()->get();

        return $result;
    }



    public function checkCompleted(AuditMaster $master)
    {
        $arr['result'] = json_decode($master->result,1);

        $currentPoint = $this->calcPoint($arr)->getResult();
        
        $complete = $master->audit->complete;
        
        $master->completed = $complete < $currentPoint ? true : false;
      
        $master->save();
    }



    public function calcPoint(array $data) : CalcPoint
    {
        $calc = new CalcPoint($data['result']);

        return $calc;
    }



    public function getResponseCount(AuditMaster $master)
    {
        return $master->getResponseCount();
    }



    public function tryClose(AuditMaster $master)
    {
        $questionCount = $master->audit->questions->count();

        $result = $this->getResponseCount($master);

        if($questionCount == $result)
            $master->closeStatus();
    }



    public function create(AuditMasterDTO $dto, )
    {
        $data = $dto->getAll();

        $calc = $this->calcPoint($data);
        $data['point'] = $calc->getResult();
        $data['total'] = $calc->getTotal();
        $data['positive'] = $calc->getPositive();

        $data['author_id'] = Auth::id();
        
        $data['positive_count'] = count($data['result']['positive']);

        $data['result'] = json_encode($data['result']); 

        $audit = AuditMaster::create($data);

        $this->checkCompleted($audit);

        $this->tryClose($audit);

        return $audit;
    }



    public function update(int $id, AuditMasterDTO $dto)
    {   
        $data = $dto->getAll();
        
        $audit = $this->getById($id);
        
        $data['author_id'] = $audit->author_id ?? Auth::id();

        $calc = $this->calcPoint($data);
        $data['point'] = $calc->getResult();
        $data['total'] = $calc->getTotal();
        $data['positive'] = $calc->getPositive();
        
        $data['positive_count'] = count($data['result']['positive']);

        $data['result'] = json_encode($data['result']);

        $audit->fill($data);

        if($audit->isDirty())
            $audit->save();

        $this->checkCompleted($audit);

        $this->tryClose($audit);

        return $audit;
    }



    public function paginate(array $data)
    {
        $query = AuditMaster::query()->select('audit_masters.*');

        $filter = app()->make(AuditMasterFilter::class, ['queryParams' => $data]);

        $query->withTrashed();

        $query->filter($filter);

        $query->with([
            'audit' => function($q){
                $q->with(['appeal'])->withCount('questions');
            },
            'author', 
            'trafic' => function($q) {
                $q->with(['structure', 'salon', 'manager'],)->withTrashed();
            },
            'record' => function($q) {
                $q->select('id','master_id');
            },
        ]);
        
        $query->orderBy('audit_masters.id', 'DESC');

        $masters = $query->simplePaginate(25);
        
        return $masters;
    }



    public function count(array $data)
    {
        $query = AuditMaster::query();

        $filter = app()->make(AuditMasterFilter::class, ['queryParams' => $data]);

        $query->withTrashed();

        $query->filter($filter);

        $query->addSelect(
            DB::raw('cast(IFNULL(sum(if(audit_masters.status = "wait", 1, 0)), 0) as integer) as _wait'),
            DB::raw('cast(IFNULL(sum(if(audit_masters.status = "arbitr", 1, 0)), 0) as integer) as _arbitr'),   

            DB::raw('cast(IFNULL(sum(if(audit_masters.completed = 1 and audit_masters.status = "close", 1, 0)),0) as integer) _completed'),
            DB::raw('cast(sum(if(audit_masters.completed = 1 and audit_masters.status = "close", audit_masters.point, 0)) as integer) as _completed_avg'),
            DB::raw('cast(sum(if(audit_masters.completed = 1 and audit_masters.status = "close", audits.bonus, 0)) as integer) as _completed_bonus'),

            DB::raw('cast(IFNULL(sum(if(audit_masters.completed = 0 and audit_masters.status = "close", 1, 0)),0) as integer) _fail'),
            DB::raw('cast(sum(if(audit_masters.completed = 0 and audit_masters.status = "close", audit_masters.point, 0)) as integer) as _fail_avg'),
            DB::raw('cast(sum(if(audit_masters.completed = 0 and audit_masters.status = "close", audits.malus,0)) as integer) as _fail_malus'),

            DB::raw('cast(IFNULL(sum(if(audit_masters.status = "close", 1, 0)),0) as integer) as _close'),
            DB::raw('cast(sum(if(audit_masters.status = "close", audit_masters.point, 0)) as integer) as _close_avg'),
            DB::raw('cast(sum(if(audit_masters.status = "close", audits.award, 0)) as integer) as _close_award'),
        );

        $res = $query->first();

        $res['_completed_avg']  = round(NumberHelper::division($res['_completed_avg'],$res['_completed']) ,2);
        $res['_fail_avg']       = round(NumberHelper::division($res['_fail_avg'],$res['_fail']) ,2);
        $res['_close_avg']      = round(NumberHelper::division($res['_close_avg'],$res['_close']) ,2);
        
        return $res;
    }
}


