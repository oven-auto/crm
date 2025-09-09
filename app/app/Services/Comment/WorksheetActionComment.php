<?php

namespace App\Services\Comment;

use App\Models\Client;
use App\Models\Interfaces\CommentInterface;

Class WorksheetActionComment extends AbstractComment
{
    public function __construct(CommentInterface $model)
    {
        $this->data = [
            'author_id' => auth()->user()->id,
            'action_id' => $model->id,
            'type' => 0,
        ];
    }

    public function getClientContactOrInn(Client $client)
    {
        if($client->phones->first())
            return $client->phones->first()->phone;
        elseif($client->inn)
            return $client->inn->number;
    }

    public function create(CommentInterface $model)
    {
        return array_merge($this->data, [
            'text' => $model->worksheet->trafic->comment ? $model->worksheet->trafic->comment : 'Комментарий трафика отсутствует',
            'type' => 0
        ]);
    }

    public function show(CommentInterface $model)
    {
        return array_merge($this->data, [
            'text' => 'Рабочий лист просматривался пользователем.',
            'type' => 2
        ]);
    }

    public function close(CommentInterface $model)
    {
        return array_merge($this->data, [
            'text' => 'Рабочий лист закрыт.',
            'type' => 1
        ]);
    }

    public function revert(CommentInterface $model)
    {
        return array_merge($this->data, [
            'text' => 'Рабочий лист отправлен на доработку.',
            'type' => 1
        ]);
    }

    public function set_client(CommentInterface $model)
    {
        $model->load('worksheet');

        $client = $model->worksheet->client;

        return array_merge($this->data, [
            'text' => 'Новый клиент '.$client->full_name.' ('.$this->getClientContactOrInn($client).')',
            'type' => 1
        ]);
    }

    public function delete_client(CommentInterface $model)
    {
        $client = $model->worksheet->client;

        return array_merge($this->data, [
            'text' => 'Старый клиент '.$client->full_name.' ('.$this->getClientContactOrInn($client).')',
            'type' => 1
        ]);
    }

    public function register_action(CommentInterface $model)
    {
        return array_merge($this->data, [
            'text' => "Новое событие: {$model->task->name} {$model->begin_at->format('d.m.Y (H:i)')}",
            'type' => 1,
        ]);
    }

    public function confirm_action(CommentInterface $model)
    {
        return array_merge($this->data, [
            'type' => 1,
            'text' => "Подтверждение события: {$model->task->name} {$model->begin_at->format('d.m.Y (H:i)')}",
        ]);
    }

    public function abort_action(CommentInterface $model)
    {
        return array_merge($this->data, [
            'type' => 1,
            'text' => "Отмена события: {$model->task->name} {$model->begin_at->format('d.m.Y (H:i)')}",
        ]);
    }
}
