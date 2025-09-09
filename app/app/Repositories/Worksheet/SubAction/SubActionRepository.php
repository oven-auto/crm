<?php

namespace App\Repositories\Worksheet\SubAction;

use App\Classes\Telegram\Notice\TelegramNotice;
use App\Models\SubAction;
use App\Services\Worksheet\WorksheetUser;
use App\Models\User;
use \Illuminate\Database\Eloquent\Collection;
use \App\Services\Comment\Comment;

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

    public function __construct(\App\Services\Worksheet\WorksheetSubActionExecutorReporterService $service)
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
        $result = SubAction::where('worksheet_id', $worksheetId)->orderBy('id', 'DESC')->get();

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

        if ($subAction->title && $subAction->title != $oldTitle && $oldTitle)
            Comment::add($subAction, 'update');

        if ($subAction->wasRecentlyCreated)
            $this->serviceExecutors->setExecutors($subAction, auth()->user()->id);

        if (isset($data['comment']))
            $this->writeComment($subAction, $data['comment']);

        $subAction->refresh();

        $subAction->load(['comments', 'executors']);
    }



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
