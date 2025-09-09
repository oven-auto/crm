<?php

namespace App\Repositories\Audit\Services;

use App\Models\Audit\AuditQuestion;

class CalcPoint
{
    private $positive = 0;
    private $negative = 0;
    private $neutral = 0;
    private $result = 0;
    private $data = [
        'positive' => [],
        'negative' => [],
        'neutral' => []
    ];
    private $questions;
    private $total = 0;



    public function __construct( array $arr = [])
    {
        $this->calc($arr);        
    }



    private function calc(array $data = [])
    {
        $this->setData($data);
        $this->setQuestions();
        $this->setTotal();
        $this->calcPositive();
        $this->calcNegative();
        $this->calcNeutral();
        $this->calcResult();
    }



    private function setData(array $data = [])
    {
        $this->data['positive'] = $data['positive'] ?? [];
        $this->data['negative'] = $data['negative'] ?? []; 
        $this->data['neutral']  = $data['neutral'] ?? []; 
    }



    private function setQuestions()
    {
        $arr = [];
        array_walk_recursive($this->data, function($item) use (&$arr){
            $arr[] = $item;
        });

        $questions = AuditQuestion::whereIn('id', $arr)->get();

        $this->questions = $questions;
    }



    private function setTotal()
    {
        $this->total = AuditQuestion::getTotal($this->questions->first());
    }



    private function calcPositive()
    {
        foreach($this->data['positive'] as $item)
            if($this->questions->contains('id', $item))
                $this->positive += $this->questions->where('id',$item)->first()->getWeight();
    }



    private function calcNegative()
    {
        foreach($this->data['negative'] as $item)
            if($this->questions->contains('id', $item))
                $this->negative += $this->questions->where('id',$item)->first()->getWeight();
    }



    private function calcNeutral()
    {
        foreach($this->data['neutral'] as $item)
            if($this->questions->contains('id', $item))
                $this->neutral += $this->questions->where('id',$item)->first()->getWeight();
    }



    private function calcResult()
    {
        $delitel = $this->total-$this->neutral;

        $this->result = $delitel ? ($this->positive/$delitel)*100 : 0;
    }



    public function getPositive()
    {
        return round($this->positive);
    }



    public function getNegative()
    {
        return round($this->negative);
    }



    public function getNeutral()
    {
        return round($this->neutral);
    }



    public function getResult()
    {
        return round($this->result);
    }



    public function getTotal()
    {
        return round($this->total-$this->neutral);
    }
}