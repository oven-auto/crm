<?php

namespace App\Services\Comment;

use App\Models\WsmReserveIssue;

class WsmReserveIssueComment extends AbstractComment
{
    public function __construct(WsmReserveIssue $sale)
    {
        $this->data = [
            'author_id' => auth()->user()->id,
            'reserve_id' => $sale->reserve_id,
        ];
    }



    public function store(WsmReserveIssue $sale)
    {
        if($sale->wasRecentlyCreated)
            return $this->update($sale);

        $text = 'Выдача автомобиля от '.$sale->date_at->format('d.m.Y').' ('.$sale->decorator->cut_name.').';

        return array_merge($this->data, [
            'text' => $text,
            'type' => 1,
        ]);
    }



    public function update(WsmReserveIssue $sale)
    {
        $text = 'Карточка регистрации выдачи автомобиля изменена.';

        return array_merge($this->data, [
            'text' => $text,
            'type' => 1,
        ]);
    }



    public function delete(WsmReserveIssue $sale)
    {
        $text = 'Выдача автомобиля аннулирована.';

        return array_merge($this->data, [
            'text' => $text,
            'type' => 1,
        ]);
    }
}