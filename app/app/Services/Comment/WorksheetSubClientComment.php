<?php

namespace App\Services\Comment;

use App\Models\Interfaces\CommentInterface;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

Class WorksheetSubClientComment extends AbstractComment
{
    public function __construct(CommentInterface $model)
    {
        $action_id = DB::table('worksheet_actions')
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

    public function attach(CommentInterface $model)
    {
        $client = Client::find($model->client_id);
        if($client->isPerson()) 
            return array_merge($this->data, [
                'text' => 'Добавлено новое контактное лицо '.$client->full_name.' ('.$client->phones->first()->phone.')',
                'type' => 1
            ]);
        else
            return array_merge($this->data, [
                'text' => 'Добавлено новое контактное лицо '.$client->full_name,
                'type' => 1
            ]);
    }

    public function detach(CommentInterface $model)
    {
        $client = Client::find($model->client_id);
        if($client->isPerson()) 
            return array_merge($this->data, [
                'text' => 'Удалено контактное лицо '.$client->full_name.' ('.$client->phones->first()->phone.')',
                'type' => 1
            ]);
        else
            return array_merge($this->data, [
                'text' => 'Удалено контактное лицо '.$client->full_name,
                'type' => 1
            ]);
    }
}
