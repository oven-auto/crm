<?php

namespace App\Services\Comment;

use App\Models\TraficClient;

Class TraficClientComment extends AbstractComment
{
    public function __construct(TraficClient $model)
    {
        $this->data = [
            'author_id' => auth()->user()->id,
            'trafic_id' => $model->trafic_id,
        ];
    }


    
    public function create(TraficClient $model)
    {
        return array_merge($this->data, [
            'text' => 'Клиент трафика добавлен: '.$model->getToString().'.'
        ]);
    }



    public function update(TraficClient $model)
    {
        return array_merge($this->data, [
            'text' => 'Клиент трафика изменен: '.$model->getToString().'.'
        ]);
    }
}
