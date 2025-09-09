<?php

Namespace App\Repositories\Audit;

use App\Http\Filters\AuditFilter;
use App\Models\Audit\Audit;
use App\Repositories\Audit\Services\CloneService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

Class AuditRepository
{
    public function paginate(array $data)
    {
        $query = Audit::query()->select('audits.*');

        $filter = app()->make(AuditFilter::class, ['queryParams' => $data]);

        $query->filter($filter);

        $audits = $query->simplePaginate(25);

        return $audits;
    }



    /**
     * Получить список аудитов по шаблону 
     */
    public function getAll(array $data) : Collection
    {
        $query = Audit::query()->select('audits.*');
        
        $filter = app()->make(AuditFilter::class, ['queryParams' => $data]);
        
        $query->filter($filter);
        
        $audits = $query->get();
        
        return $audits;
    }



    /**
     * Создать аудит
     */
    public function create(array $data) : Audit
    {
        $audit = DB::transaction(function() use($data){
            $data['author_id'] = auth()->user()->id;
            $data['editor_id'] = auth()->user()->id;
        
            $audit = Audit::create(Arr::except($data, 'chanels'));

            $audit->chanels()->sync($data['chanels']);

            return $audit;
        }, 3);
        
        return $audit;
    }



    /**
     * Изменить аудит
     */
    public function update(int $id, array $data) : Audit
    {
        $audit = DB::transaction(function() use($id, $data){
            $audit = Audit::findOrFail($id);

            $audit->fill($data);

            if($audit->isDirty())
            {
                $audit->editor_id = auth()->user()->id;
                $audit->save();
            }

            $audit->chanels()->sync($data['chanels']);

            return $audit;
        }, 3);
        
        return $audit;
    }



    /**
     * Получить аудит по ID
     */
    public function getById(int $id, array|null $with = null) : Audit
    {
        $with = $with ?? ['chanels'];

        $audit = Audit::with($with)->findOrFail($id);

        return $audit;
    }



    /**
     * Удалить аудит (все связи каскадом)
     */
    public function delete(int $id) : Audit
    {
        $res = DB::transaction(function() use ($id){
            $audit = Audit::findOrFail($id);

            $res = $audit->replicate();
            
            $audit->delete();

            return $res;
        }, 3);
        
        return $res;
    }



    public function restore(int $id) : Audit
    {
        $audit = Audit::onlyTrashed()->findOrFail($id);

        $audit->restore();

        return $audit;
    }



    public function clone(int $id)
    {
        $clone = DB::transaction(function() use($id){
            $audit = $this->getById(id: $id);

            $audit->load(['questions' => function($q){
                $q->with(['subquestions.answers', 'answers']);
            }]);

            $clone = $audit->replicate();

            $clone->push();

            $clone->chanels()->sync($audit->chanels->pluck('id'));

            $service = new CloneService();

            $service->cloneQuestion(clone: $clone, origin: $audit);
            
            return $clone;
        }, 3);
        
        return $clone;
    }
}