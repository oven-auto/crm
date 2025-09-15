<?php

namespace App\Repositories\Worksheet\SubAction;

use App\Classes\Telegram\Notice\TelegramNotice;
use App\Models\SubAction;
use App\Services\Worksheet\WorksheetUser;
use App\Models\User;
use \Illuminate\Database\Eloquent\Collection;
use \App\Services\Comment\Comment;
use App\Services\Worksheet\WorksheetSubActionExecutorReporterService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

/**
 * РЕПОЗИТОРИЙ ПОДЗАДАЧИ
 * 1 - ПОЛУЧИТЬ ВСЕ ПОДЗАДАЧИ ИЗ ВЫБРАННОГО РЛ
 * 2 - СОХРАНИТЬ ПОДЗАДАЧУ
 * 3 - ЗАКРЫТЬ ПОДЗАДАЧУ

 * 8 - ЗАПИСАТЬ КОММЕНТАРИЙ В ПОДЗАДАЧУ
 * 9 - ПОЛУЧИТЬ ВСЕ КОММЕНТАРИИ ПОДЗАДАЧИ В ВИДЕ МАССИВА
 *
 * 16-01-2024
 *
 */
class SubActionRepository
{
    private $serviceExecutors;

    public function __construct(WorksheetSubActionExecutorReporterService $service)
    {
        $this->serviceExecutors = $service;
    }



    /**
     * 1 - ПОЛУЧИТЬ ВСЕ ПОДЗАДАЧИ ИЗ ВЫБРАННОГО РЛ
     * @param int $worksheetId
     * @return Collection
     */
    public function getAllByWorksheetId(int $worksheetId): Collection
    {
        $result = SubAction::where('worksheet_id', $worksheetId)
            ->orderBy('id', 'DESC')
            ->get();

        return $result;
    }



    /**
     * 2 - СОХРАНИТЬ ПОДЗАДАЧУ
     * @param SubAction $subAction
     * @param array $data [title => string, worksheet_id => int, ?executors => array, comment => string]
     * @return void
     */
    public function save(SubAction $subAction, array $data): void
    {
        if (!$subAction->author_id)
            $subAction->author_id = auth()->user()->id;

        $oldTitle = $subAction->title;

        $subAction->fill([
            'worksheet_id' => $data['worksheet_id'],
            'title' => $data['title'],
        ])->save();
        
        Comment::add($subAction, 'create');

        if ($subAction->title && $subAction->title != $oldTitle && $oldTitle)
            Comment::add($subAction, 'update');

        if (isset($data['comment']))
            $this->writeComment($subAction, $data['comment']);

        if ($subAction->wasRecentlyCreated)
            $this->serviceExecutors->setExecutors($subAction, Auth::id());     

        $subAction->refresh();

        $subAction->load(['comments', 'executors']);
    }


    //TODO Переделать сохранение создать ДТО, РЕКВЕСТ
    // public function create(array $data)
    // {
    //     $action = app()->make(SubAction::class);

    //     $this->save1($action, $data);
    // }



    // public function save1(SubAction $action, array $data)
    // {
    //     $action->fill(Arr::only($data, ['worksheet_id', 'title']))->save();

    //     $this->writeComment($action, $data['comment']);

    //     if(count($data['executors']))
    //         $this->serviceExecutors->setExecutors($action, Auth::id());
    // }



    /**
     * 3 - ЗАКРЫТЬ ПОДЗАДАЧУ
     * @param SubAction $subAction
     * @return void
     */
    public function closeAction(SubAction $subAction): void
    {
        if ($subAction->author_id == auth()->user()->id) {
            $subAction->close();

            $users = $subAction->executors->keyBy('id')->forget(auth()->user()->id)->pluck('id')->toArray();

            TelegramNotice::run($subAction)->close()->send($users);
        }
    }



    /**
     * 8 - ЗАПИСАТЬ КОММЕНТАРИЙ В ПОДЗАДАЧУ
     * @param SubAction $subAction
     * @param string $text
     * @return void
     */
    public function writeComment(SubAction $subAction, string $text): void
    {
        $subAction->comments()->create([
            'text' => $text,
            'author_id' => auth()->user()->id,
        ]);
    }



    /**
     * 9 - ПОЛУЧИТЬ ВСЕ КОММЕНТАРИИ ПОДЗАДАЧИ В ВИДЕ МАССИВА
     * @param SubAction $subAction
     * @return  \Illuminate\Support\Collection
     */
    public function getSubActionComments(SubAction $subAction): \Illuminate\Support\Collection
    {
        return $subAction->comments->map(fn ($item) => [
            'text' => $item->text,
            'id' => $item->id,
            'author' => $item->author->cut_name,
            'created_at' => $item->created_at->format('d.m.Y (H:i)'),
        ]);
    }
}
