<?php

namespace App\Services\Comment;

use App\Models\Interfaces\CommentInterface;
use App\Models\SubAction;
use App\Models\User;

Class SubActionComment extends AbstractComment
{
    public function __construct(SubAction $subAction)
    {
        $this->data = [
            'author_id' => auth()->user()->id,
            'sub_action_id' => $subAction->id,
        ];
    }



    public function create(CommentInterface $model)
    {
        return array_merge($this->data, [
            'text' => 'Создана подзадача '.$model->title,
            'type' => 1,
        ]);
    }



    public function update(CommentInterface $model)
    {
        return array_merge($this->data, [
            'text' => 'Автор уточнил задачу, новая формулировка - '.$model->title,
            'type' => 0,
        ]);
    }



    public function close(CommentInterface $model)
    {
        return array_merge($this->data, [
            'text' => 'Задача завершена.',
            'type' => 1
        ]);
    }



    public function append_executors(CommentInterface $model, array $data = [])
    {
        if(isset($data['executors']))
            $users = User::whereIn('id', $data['executors'])->get()->map(fn($item) => $item->cut_name)->toArray();
            return array_merge($this->data, [
                'text' => 'В задачу добавлен новый участник: '.join(' ', $users),
                'type' => 1,
            ]);
    }



    public function detach_executor(CommentInterface $model, array $data = [])
    {
        if(isset($data['executor']))
        {
            $user = User::find($data['executor']);
            if($user)
                return array_merge($this->data, [
                    'text' => 'Удален участник: '.$user->cut_name,
                    'type' => 1,
                ]);
        }
    }



    public function append_report(CommentInterface $model, array $data = [])
    {
        if(isset($data['reporter']))
        {
            $user = User::find($data['reporter']);
            if($user)
                return array_merge($this->data, [
                    'text' => 'Участник отчитался и больше не отслеживает задачу.',
                    'type' => 1,
                ]);
        }
    }

    public function detach_report(CommentInterface $model, array $data = [])
    {
        if(isset($data['reporter']))
        {
            $user = User::find($data['reporter']);
            if($user)
                return array_merge($this->data, [
                    'text' => 'Отчет об исполнении отозван, '.$user->cut_name.' снова отслеживает задачу.',
                    'type' => 1
                ]);
        }
    }
}
