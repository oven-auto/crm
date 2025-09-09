<?php

namespace App\Repositories\Audit\Services;

use App\Models\Audit\Audit;
use App\Models\Audit\AuditQuestion;
use App\Models\Audit\AuditSubQuestion;
use App\Repositories\Audit\QuestionRepository;
use App\Repositories\Audit\SubAnswerRepository;
use App\Repositories\Audit\SubQuestionRepository;

Class CloneService
{
    public function cloneQuestion(Audit $clone, Audit $origin) : void
    {
        $questions = collect(SortTree::tree($origin->questions, $origin->questions->whereNull('sort')->first()));

        $questions->map(function($item) use ($clone){
            $questionRepo = new QuestionRepository();

            $questionArr = array_merge(
                ['answers' => $item->answers->only(['positive', 'negative', 'neutral'])],
                ['audit_id' => $clone->id], 
                $item->only(['name', 'text', 'weight', 'is_stoped'])
            );
            
            $cloneQuestion = $questionRepo->create($questionArr);
            
            $this->cloneSubQuestion($cloneQuestion, $item);        
        });
    }



    public function cloneSubQuestion(AuditQuestion $clone, AuditQuestion $origin) : void
    {
        $subquestions = collect(SortTree::tree($origin->subquestions, $origin->subquestions->whereNull('sort')->first()));

        $subquestions->map(function($item) use ($clone){
            $subQuestionRepo = new SubQuestionRepository();

            $subQArr = array_merge(
                ['question_id' => $clone->id], 
                $item->only(['multiple', 'text',])
            );

            $subQuestion = $subQuestionRepo->create($subQArr);  
         
            $this->cloneSubAnswer($subQuestion, $item);
        });   
    }



    public function cloneSubAnswer(AuditSubQuestion $clone, AuditSubQuestion $origin) : void
    {
        $subAnswers = collect(SortTree::tree($origin->answers, $origin->answers->whereNull('sort')->first()));

        $subAnswers->map(function($item) use($clone){
            $subAnswerRepo = new SubAnswerRepository();

            $subAnswerArr = array_merge(
                ['sub_id' => $clone->id], 
                $item->only(['text',])
            );
            
            $subAnswerRepo->create($subAnswerArr);
        });
    }
}