<?php

namespace App\Services\Comment;

use App\Models\Interfaces\CommentInterface;

Class WorksheetFileComment extends AbstractComment
{
    public function __construct(CommentInterface $model)
    {
        $this->data = [
            'author_id' => auth()->user()->id,
            'action_id' => $model->worksheet->last_action->id,
            'type' => 0,
        ];
    }

    public function create(CommentInterface $model)
    {
        return array_merge($this->data, [
            'text' => 'В рабочий лист добавлены файлы.',
            'type' => 1
        ]);
    }

    public function delete(CommentInterface $model)
    {
        return array_merge($this->data, [
            'text' => 'Из рабочего листа удалены файлы.',
            'type' => 1
        ]);
    }
}
