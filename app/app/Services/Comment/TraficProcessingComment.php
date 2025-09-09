<?php

namespace App\Services\Comment;

use App\Models\Interfaces\CommentInterface;

Class TraficProcessingComment extends AbstractComment
{
    public function __construct(CommentInterface $model)
    {
        $this->data = [
            'author_id' => auth()->user()->id,
            'trafic_id' => $model->trafic_id,
        ];
    }

    public function create(CommentInterface $model)
    {
        return array_merge($this->data, [
            'text' => 'Аудит добавлен.'
        ]);
    }

    public function update(CommentInterface $model)
    {
        return array_merge($this->data, [
            'text' => 'Аудит изменен.'
        ]);
    }

    public function show(CommentInterface $model)
    {
        return array_merge($this->data, [
            'text' => 'Аудит просматривался.'
        ]);
    }
}
