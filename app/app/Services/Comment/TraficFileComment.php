<?php

namespace App\Services\Comment;

use App\Models\TraficFile;

Class TraficFileComment extends AbstractComment
{
    public function __construct(TraficFile $model)
    {
        $this->data = [
            'author_id' => auth()->user()->id,
            'trafic_id' => $model->trafic_id,
        ];
    }


    
    public function create(TraficFile $model)
    {
        return array_merge($this->data, [
            'text' => 'Файл добавлен '.$model->name.'.'
        ]);
    }



    public function delete(TraficFile $model)
    {
        return array_merge($this->data, [
            'text' => 'Файл удален '.$model->name.'.'
        ]);
    }
}
