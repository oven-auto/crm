<?php

namespace App\Services\Comment;

use App\Models\TraficControl;

Class TraficControlComment extends AbstractComment
{
    public function __construct(TraficControl $model)
    {
        $this->data = [
            'author_id' => auth()->user()->id,
            'trafic_id' => $model->trafic_id,
        ];
    }


    
    public function create(TraficControl $model)
    {
        return array_merge($this->data, [
            'text' => 'Точки контроля добавлены: '.$model->begin_at->format('d.m.Y (H:i)').' - '.$model->end_at->format('d.m.Y (H:i)'.'.')
        ]);
    }



    public function update(TraficControl $model)
    {
        return array_merge($this->data, [
            'text' => 'Точки контроля изменены: '.$model->begin_at->format('d.m.Y (H:i)').' - '.$model->end_at->format('d.m.Y (H:i)'.'.')
        ]);
    }
}
