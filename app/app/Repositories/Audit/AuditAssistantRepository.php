<?php

namespace App\Repositories\Audit;

use App\Models\Audit\AuditAssist;
use App\Repositories\Audit\DTO\AuditAssistantDTO;
use App\Repositories\Audit\DTO\AuditMasterDTO;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuditAssistantRepository
{
    public function __construct(
        private AuditMasterRepository $masterRepo,
    )
    {
        
    }



    public function getList(array $data = [])
    {
        $query = AuditAssist::query()->select('id', 'trafic_id', 'audit_id');

        if(isset($data['trafic_id']))
            $query->where('trafic_id', $data['trafic_id']);

        $assists = $query->get();

        return $assists;
    }



    public function getById(int $id)
    {
        $assistant = AuditAssist::findOrFail($id);

        return $assistant;
    }



    public function checkExist(array $data)
    {
        $existed = AuditAssist::query()
            ->where('trafic_id', $data['trafic_id'])
            ->where('audit_id', $data['audit_id'])
            ->first();

        if($existed)
            throw new \Exception('Уже существует ассистент для этого аудита в этом трафике.');
    }



    public function create(AuditAssistantDTO $data)
    {   
        $data = $data->getAll();

        $assist = DB::transaction(function() use($data){
            $data['author_id'] = Auth::id();
            
            $this->checkExist($data);
            
            $assist = AuditAssist::create($data);

            $this->masterRepo->create(new AuditMasterDTO(Arr::only($data,['trafic_id', 'audit_id'])));

            return $assist;
        }, 3);
        
        return $assist;
    }



    public function update(int $id, AuditAssistantDTO $data)
    {
        $data = $data->getAll();
        
        $assistant = $this->getById($id);

        $assistant->fill($data);

        if($assistant->isDirty())
            $assistant->save();

        return $assistant;
    }
}