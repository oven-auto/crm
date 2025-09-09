<?php

namespace App\Services\Comment;

use App\Models\Interfaces\CommentInterface;
use App\Models\Client;

Class ClientUnionComment extends AbstractComment
{
    public function __construct(CommentInterface $model)
    {
        $this->data = [
            'author_id' => auth()->user()->id,
            'client_id' => $model->parent,
        ];
    }

    public function create(CommentInterface $model)
    {
        $client = Client::find($model->client_id);
        return array_merge($this->data, [
            'text' => 'Добавлен связанный контакт: '.$client->full_name,
        ]);
    }

    public function delete(CommentInterface $model)
    {
        $client = Client::find($model->client_id);
        return array_merge($this->data, [
            'text' => 'Удален связанный контакт: '.$client->full_name,
        ]);
    }
}
