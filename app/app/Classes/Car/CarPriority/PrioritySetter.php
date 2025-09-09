<?php

namespace App\Classes\Car\CarPriority;

use App\Models\Car;
use Exception;

Class PrioritySetter
{
    private const STATUSES = [
        'preorder'          => 1,                            //предзаказ
        'newentry'          => 2,                            //свежее поступление
        'paidentry'         => 3,                            //платный период
        'overdueentry'      => 4,                            //просроченная дебиторка
        'problem'           => 5,                            //проблемный склад
        'toxic'             => 6,    
    ];

    public $car;
    public $now;
    public $states;

    public function __construct(Car $car)
    {
        $this->car = $car;
        $this->states = [];
        $this->now = now();
    }



    /**
     * Установить статус
     */
    private function setPriority(string $key) : void
    {
            $this->car->priority->fill([
                'priority_id' => $key
            ])->save();
    }



    public static function make(Car $car)
    {
        return new self($car);
    }



    /**
     * Добавить состояние в очередь
     */
    public function appendState($state)
    {
        $this->states[] = $state;
    }



    /**
     * Есть резерв
     */
    public function hasReserve()
    {
        return $this->car->isReserved() ? 1 : 0;
    }



    /**
     * Есть дата прихода на склад
     */
    public function hasStockDate()
    {
        return $this->car->stockDate() ? 1 : 0;
    }



    /**
     * Есть выкуп
     */
    public function hasRansom()
    {
        return $this->car->hasRansom() ? 1 : 0;
    }



    /**
     * Есть дата платного периода
     */
    public function hasPaidDate()
    {
        return $this->car->paid_date ? 1 : 0;
    }



    /**
     * Есть дата контроля оплаты
     */
    public function hasControllPaidDate()
    {
        return $this->car->control_paid_date ? 1 : 0;
    }



    /**
     * Дата прихода на склад больше даты создания резерва
     */
    public function isPreorder()
    {
        return $this->car->reserve->created_at < $this->car->stockDate();
    }



    private function fasade()
    {
        PreorderState::check($this, self::STATUSES['preorder']);
        NewEntryState::check($this, self::STATUSES['newentry']);
        PaidEntryState::check($this, self::STATUSES['paidentry']);
        OverdueEntryState::check($this, self::STATUSES['overdueentry']);
        ProblemEntryState::check($this, self::STATUSES['problem']);
        ToxicState::check($this, self::STATUSES['toxic']);

        $this->save();
    }
    
    
    
    public function save()
    {
        $max = -1;
        $res = null;
        foreach($this->states as $state)
            if($state->getWeight() > $max)
            {
                $max = $state->getWeight();
                $res = $state;
            }
        
        if($res instanceof PriorityStateInterface)
            $this->setPriority($res->getPriority());
    }



    public function handler()
    {
        if(!$this->hasStockDate())
            $this->car->priority->delete();
        else
        {
            if($this->car->isReserved())
            {
                if($this->isPreorder())
                {
                    PreorderState::check($this, self::STATUSES['preorder']);    
                    $this->save();
                }
                return;
            }
            $this->fasade();
        }
    }



    public function checkPriority()
    {
        $this->handler();
    }
}