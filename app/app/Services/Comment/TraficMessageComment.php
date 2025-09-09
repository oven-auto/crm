<?php

namespace App\Services\Comment;

use App\Models\TraficMessage;

Class TraficMessageComment extends AbstractComment
{
    public function __construct(TraficMessage $model)
    {
        $this->data = [
            'author_id' => auth()->user()->id,
            'trafic_id' => $model->trafic_id,
        ];
    }


    
    public function create(TraficMessage $model)
    {
        return array_merge($this->data, [
            'text' => 'Комментарий добавлен: '.$model->message.'.'
        ]);
    }



    public function update(TraficMessage $model)
    {
        return array_merge($this->data, [
            'text' => 'Комментарий изменен: '.$model->message.'.'
        ]);
    }
}
