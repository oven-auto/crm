<?php

namespace App\Services\Comment;

use App\Models\Interfaces\CommentInterface;

Class WorksheetLinkComment extends AbstractComment
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
            'text' => 'В рабочий лист добавлена ссылка.',
            'type' => 1
        ]);
    }

    public function delete(CommentInterface $model)
    {
        return array_merge($this->data, [
            'text' => 'Из рабочего листа удалена ссылка.',
            'type' => 1
        ]);
    }
}
