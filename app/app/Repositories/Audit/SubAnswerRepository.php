<?php

namespace App\Repositories\Audit;

use App\Models\Audit\AuditSubAnswer;
use App\Repositories\Audit\Services\SortTree;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

Class SubAnswerRepository
{
    public function get(array $data)
    {
        $query = AuditSubAnswer::select('*');

        if(isset($data['sub_id']))
            $query->where('sub_id', $data['sub_id']);

        $subs = $query->get();

        $first = $subs->whereNull('sort')->first();
        
        $res = collect(SortTree::tree($subs, $first));
        
        return $res;
    }



    public function getById(int $id)
    {
        return AuditSubAnswer::findOrFail($id);
    }



    public function getLast(array $data)
    {
        $answers = $this->get(Arr::only($data, ['sub_id']));

        return $answers->count() ? $answers->last()->id : null;
    }



    public function create(array $data)
    {
        $data['sort'] = $this->getLast($data);

        $answer = AuditSubAnswer::create($data);

        return $answer;
    }



    public function update(int $id, array $data)
    {
        $answer = $this->getById(id: $id);

        $answer->fill($data);

        if($answer->isDirty())
            $answer->save();

        return $answer;
    }



    public function delete(int $id)
    {
        $answer = $this->getById(id: $id);

        SortTree::changeSortOnDelete($answer);

        $answer->delete();

        return 1;
    }



    public function sort(array $data)
    {
        DB::transaction(function() use ($data){
            $object = AuditSubAnswer::findOrFail($data['answers']['first']);
            $after = AuditSubAnswer::find($data['answers']['second']);                
            $root = AuditSubAnswer::where('sub_id', $object->sub_id)->whereNull('sort')->first();

            if($after && ($object->sub_id != $after->sub_id))
                throw new \Exception('Вы пытаетесь отсортировать ответы разных аудитов.');
           
            if($after)
                SortTree::sortAfter($object, $after);
            elseif($root)
                SortTree::sortPrefer($object, $root);
        }, 3);  
    }
}