<?php

namespace App\Services\Comment;

use App\Models\Interfaces\CommentInterface;
use App\Models\USer;

Class WorksheetExecutorComment extends AbstractComment
{
    public function __construct(CommentInterface $model)
    {
        $action_id = \DB::table('worksheet_actions')
            ->select('id')
            ->where('worksheet_id', $model->worksheet_id)
            ->orderBy('worksheet_actions.id', 'DESC')
            ->limit(1)
            ->get()
            ->first()
            ->id;

        $this->data = [
            'author_id' => auth()->user()->id,
            'action_id' => $action_id,
            'type' => 0,
        ];
    }



    public function attach(CommentInterface $model, \Illuminate\Support\Collection $data)
    {
        $names = $data->map(fn($item) => $item->cut_name)->toArray();
        return array_merge($this->data, [
            'text' => 'В рабочий лист добавлены новые участники: '.join(', ',$names),
            'type' => 1
        ]);
    }



    public function detach(CommentInterface $model)
    {
        $user = User::find($model->user_id);
        return array_merge($this->data, [
            'text' => 'В рабочем листе удален участник '.$user->cut_name,
            'type' => 1
        ]);
    }



    public function report(CommentInterface $model)
    {
        $user = User::find($model->user_id);
        return array_merge($this->data, [
            'text' => 'Участник отчитался и больше не отслеживает работу с клиентом.',
            'type' => 1
        ]);
    }



    public function deport(CommentInterface $model)
    {
        $user = User::find($model->user_id);
        return array_merge($this->data, [
            'text' => 'Отчет об исполнении отозван, '.$user->cut_name.' снова отслеживает работу с клиентом.',
            'type' => 1
        ]);
    }
}
