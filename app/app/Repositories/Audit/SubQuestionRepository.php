<?php

namespace App\Repositories\Audit;

use App\Models\Audit\AuditSubQuestion;
use App\Repositories\Audit\Services\SortTree;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SubQuestionRepository
{
    public function getLast(array $data)
    {
        $questions = $this->get(Arr::only($data, ['question_id']));

        return $questions->count() ? $questions->last()->id : null;
    }



    public function get(array $data)
    {
        $query = AuditSubQuestion::select('*');

        if(isset($data['question_id']))
            $query->where('question_id', $data['question_id']);

        $subs = $query->get();

        $first = $subs->whereNull('sort')->first();
        
        $res = collect(SortTree::tree($subs, $first));
        
        return $res;
    }



    public function getById(int $id)
    {
        return AuditSubQuestion::findOrFail($id);
    }



    public function create(array $data)
    {
        $data['sort'] = $this->getLast($data);

        $sub = AuditSubQuestion::create($data);

        return $sub;
    }



    public function update(int $id, array $data)
    {
        $sub = $this->getById($id);

        $sub->fill($data);

        if($sub->isDirty())
            $sub->save();

        return $sub;
    }



    public function delete(int $id)
    {
        $sub = $this->getById(id: $id);

        SortTree::changeSortOnDelete($sub);

        $sub->delete();

        return 1;
    }



    public function sort(array $data)
    {
        DB::transaction(function() use ($data){
            $object = AuditSubQuestion::findOrFail($data['questions']['first']);
            $after = AuditSubQuestion::find($data['questions']['second']);                
            $root = AuditSubQuestion::where('question_id', $object->question_id)->whereNull('sort')->first();

            if($after && ($object->question_id != $after->question_id))
                throw new \Exception('Вы пытаетесь отсортировать вопросы разных аудитов.');
           
            if($after)
                SortTree::sortAfter($object, $after);
            elseif($root)
                SortTree::sortPrefer($object, $root);
        }, 3);  
    }
}