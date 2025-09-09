<?php

namespace App\Services\Car;

use App\Helpers\String\StringHelper;
use App\Models\Car;
use Carbon\Carbon;

class CarLogisticStateService
{
    private $title;
    private $date;
    private $weight;
    private $now;
    private $car;

    private $carbon_date;

    public function __construct(Car $car)
    {
        $this->now = now();
        $this->car = $car;
        $this->currentCarState();
    }



    public function setData(array $array)
    {
        $this->title = $array['title'];
        $this->date = $array['date'];
        $this->weight = $array['weight'];
        $this->carbon_date = Carbon::createFromFormat('d.m.Y', $this->date);
    }



    public function getWithCountDay($plusDay = 0)
    {
        $days = $this->now->diffInDays($this->carbon_date) + $plusDay;
        return $this->title . ' ' . $days . ' ' . StringHelper::dayWord($days);
    }



    public function getWithDate()
    {
        return $this->title . ' ' . $this->date;
    }



    public function getWithCountDayOrDate()
    {
        if ($this->now > $this->carbon_date)
            return $this->getWithCountDay(1);
        return $this->getWithDate();
    }



    public function getOnlyStatus()
    {
        return $this->title;
    }



    public function getStatusString()
    {
        return match ($this->weight) {
            0 => $this->getWithCountDay(1),
            1 => $this->getWithCountDay(1),
            2 => $this->getWithCountDayOrDate(),
            3 => $this->getWithDate(),
            4 => $this->getOnlyStatus(),
            5 => $this->getOnlyStatus(),
            6 => $this->getWithDate(),
            7 => $this->getWithCountDay(1),
            8 => $this->getWithCountDay(1),
            9 => $this->getWithDate(),
            10 => $this->getWithDate(),
            11 => $this->getWithCountDay(1),
            17 => $this->getWithCountDay(1),
            19 => $this->getWithDate(),
        };
    }



    public function getLastLogisticState()
    {
        return $this->car->logistic_dates->where('state.state', $this->car->logistic_dates->max('state.state'))->first();
    }



    public function currentCarState()
    {
        $state = $this->getLastLogisticState();

        $state_status = $this->car->state_status;

        $this->setData([
            'title' => 'В заявке',
            'date' => $this->car->created_at->format('d.m.Y'),
            'weight' => 0
        ]);

        if ($state_status) {
            if ($state)
                $this->setData([
                    'title' => $state_status->description,
                    'date' => $state->date_at->format('d.m.Y'),
                    'weight' => $state_status->id
                ]);
            else
                $this->setData([
                    'title' => 'В заявке',
                    'date' => $this->car->created_at->format('d.m.Y'),
                    'weight' => 0
                ]);
        }


        if ($this->car->isIssued())
            $this->setData([
                'title' => 'Выдан',
                'date' => $this->car->reserve->getIssueDate(),
                'weight' => 9,
            ]);

        if ($this->car->isSaled())
            $this->setData([
                'title' => 'Продан',
                'date' => $this->car->reserve->getSaleDate(),
                'weight' => 10,
            ]);
    }
}
