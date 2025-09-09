<?php

namespace App\Services\Comment;

use App\Models\TraficLink;

Class TraficLinkComment extends AbstractComment
{
    public function __construct(TraficLink $model)
    {
        $this->data = [
            'author_id' => auth()->user()->id,
            'trafic_id' => $model->trafic_id,
        ];
    }

    

    public function create(TraficLink $model)
    {
        return array_merge($this->data, [
            'text' => 'Ссылка добавлена '.$model->text.'.'
        ]);
    }



    public function delete(TraficLink $model)
    {
        return array_merge($this->data, [
            'text' => 'Ссылка удалена '.$model->text.'.'
        ]);
    }
}
