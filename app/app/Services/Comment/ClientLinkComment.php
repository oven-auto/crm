<?php

namespace App\Services\Comment;

use App\Models\Interfaces\CommentInterface;

Class ClientLinkComment extends AbstractComment
{
    public function __construct(CommentInterface $model)
    {
        $this->data = [
            'author_id' => auth()->user()->id,
            'client_id' => $model->client_id,
        ];
    }

    public function create(CommentInterface $model)
    {
        return array_merge($this->data, [
            'text' => 'Добавлена ссылка.'
        ]);
    }

    public function update(CommentInterface $model)
    {
        return array_merge($this->data, [
            'text' => 'Изменена ссылка.'
        ]);
    }

    public function delete(CommentInterface $model)
    {
        return array_merge($this->data, [
            'text' => 'Удалена ссылка.'
        ]);
    }
}
