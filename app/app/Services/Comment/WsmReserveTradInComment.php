<?php

namespace App\Services\Comment;

use App\Models\UsedCar;
use App\Models\WsmReserveTradIn;

Class WsmReserveTradInComment extends AbstractComment
{
    private $usedCar;

    public function __construct(WsmReserveTradIn $tradein)
    {
        $this->data = [
            'author_id' => auth()->user()->id,
            'reserve_id' => $tradein->reserve_id,
        ];

        $this->usedCar = UsedCar::find($tradein->used_car_id);
    }



    public function store(WsmReserveTradIn $tradein)
    {
        $price = number_format($this->usedCar->purchase_price, 0, '', ' ').'р.';
        $name = $this->usedCar->brand->name.' '.$this->usedCar->mark->name;
        $date = now()->format('d.m.Y');
        $text = 'В сделку добавлен ТИ на новый автомобиль от '.$date.' ('.$price.' '.$name.').';

        return array_merge($this->data, [
            'text' => $text,
            'type' => 1,
        ]);
    }



    public function delete(WsmReserveTradIn $tradein)
    {
        $price = number_format($this->usedCar->purchase_price, 0, '', ' ').'р.';
        $name = $this->usedCar->brand->name.' '.$this->usedCar->mark->name;
        $date = now()->format('d.m.Y');
        $text = 'Из сделки удален ТИ на новый автомобиль от '.$date.' ('.$price.' '.$name.').';

        return array_merge($this->data, [
            'text' => $text,
            'type' => 1,
        ]);
    }
}