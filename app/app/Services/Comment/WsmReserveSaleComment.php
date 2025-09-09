<?php

namespace App\Services\Comment;

use App\Models\WsmReserveSale;

class WsmReserveSaleComment extends AbstractComment
{
    public function __construct(WsmReserveSale $sale)
    {
        $this->data = [
            'author_id' => auth()->user()->id,
            'reserve_id' => $sale->reserve_id,
        ];
    }



    public function store(WsmReserveSale $sale)
    {
        if($sale->wasRecentlyCreated)
            return $this->update($sale);

        $text = 'Продажа автомобиля от '.$sale->date_at->format('d.m.Y').' ('.$sale->decorator->cut_name.').';

        return array_merge($this->data, [
            'text' => $text,
            'type' => 1,
        ]);
    }



    public function update(WsmReserveSale $sale)
    {
        $text = 'Карточка регистрации продажи автомобиля изменена.';

        return array_merge($this->data, [
            'text' => $text,
            'type' => 1,
        ]);
    }



    public function delete(WsmReserveSale $sale)
    {
        $text = 'Продажа автомобиля аннулирована.';

        return array_merge($this->data, [
            'text' => $text,
            'type' => 1,
        ]);
    }
}