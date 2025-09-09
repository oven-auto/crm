<?php

namespace App\Services\Comment;

use App\Models\Interfaces\CommentInterface;

Class ClientComment extends AbstractComment
{
    public function __construct(CommentInterface $model)
    {
        $this->data = [
            'author_id' => auth()->user()->id,
            'client_id' => $model->id,
        ];
    }

    public function show(CommentInterface $model)
    {
        return array_merge($this->data, [
            'text' => 'Клиент просматривался.'
        ]);
    }

    public function create(CommentInterface $model)
    {
        return array_merge($this->data, [
            'text' => 'Клиент создан.'
        ]);
    }

    public function update(CommentInterface $model)
    {
        return array_merge($this->data, [
            'text' => 'Клиент изменен.'
        ]);
    }

    public function delete(CommentInterface $model)
    {
        return array_merge($this->data, [
            'text' => 'Клиент удален.'
        ]);
    }
}
