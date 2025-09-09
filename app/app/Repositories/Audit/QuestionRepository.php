<?php

namespace App\Repositories\Audit;

use App\Models\Audit\AuditQuestion;
use App\Repositories\Audit\Services\SortTree;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

Class QuestionRepository
{
    public static function make()
    {
        return new self;
    }



    public function getLast(array $data)
    {
        $questions = $this->get(Arr::only($data, ['audit_id']));

        return $questions->count() ? $questions->last()->id : null;
    }



    public function getAllWithOutSort(array $data)
    {
        $query = AuditQuestion::select('audit_questions.*')->with(['audit','answers']);
            
        if(isset($data['audit_id']))
            $query->where('audit_id', $data['audit_id']);

        if(isset($data['trash']))
            $query->onlyTrashed();

        $questions = $query->get();

        return $questions;
    }



    public function get(array $data) : Collection
    {
        $questions = $this->getAllWithOutSort($data);
        
        $first = $questions->whereNull('sort')->first();
       
        $res = collect(SortTree::tree($questions, $first));
        
        return $res;
    }



    public function getById(int $id) : AuditQuestion
    {
        $question = AuditQuestion::findOrFail($id);
        
        return $question;
    }



    public function create(array $data) : AuditQuestion
    {
        $question = DB::transaction(function() use ($data) {
            $data['author_id'] = auth()->user()->id;

            $data['sort'] = $this->getLast(Arr::only($data, ['audit_id']));
            
            $question = AuditQuestion::create(Arr::except($data, ['answers']));
           
            $question->answers()->updateOrCreate(
                ['question_id' => $question->id],
                $data['answers']
            );

            return $question;
        }, 3);

        return $question;
    }



    public function update(int $id, array $data) : AuditQuestion
    {
        $question = DB::transaction(function() use($data, $id){
            $question = AuditQuestion::findOrFail($id);

            $data['weight'] = $data['weight'] ?? null;
            
            $question->fill($data);

            if($question->isDirty())
                $question->save();

            $question->answers()->updateOrCreate(
                ['question_id' => $question->id],
                $data['answers']
            );

            return $question;
        }); 
        
        return $question;
    }



    public function delete(int $id) : AuditQuestion
    {   
        $res = DB::transaction(function() use($id){
            $question = AuditQuestion::findOrFail($id);
       
            SortTree::changeSortOnDelete($question);

            $res = $question->replicate();

            $question->delete();

            return $res;
        }, 3);
        
        return $res;
    }



    public function restore(int $id) : AuditQuestion
    {
        $question = DB::transaction(function() use ($id){
            $question = AuditQuestion::onlyTrashed()->findOrFail($id);
        
            $question->restore();

            $question->fill(['sort' => $this->getLast(['audit_id' => $question->audit_id])])->save();

            return $question;
        }, 3);

        return $question;
    }



    public function sort(array $data)
    {       
        DB::transaction(function() use ($data){
            $object = AuditQuestion::findOrFail($data['questions']['first']);
            $after = AuditQuestion::find($data['questions']['second'] ?? null);                
            $root = AuditQuestion::where('audit_id', $object->audit_id)->whereNull('sort')->first();

            if($after && ($object->audit_id != $after->audit_id))
                throw new \Exception('Вы пытаетесь отсортировать вопросы разных аудитов.');
            
            if($after)
                SortTree::sortAfter($object, $after);
            else
                SortTree::sortPrefer($object, $root);
        }, 3);   
    }



    
}