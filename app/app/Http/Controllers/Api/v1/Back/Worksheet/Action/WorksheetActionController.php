<?php

namespace App\Http\Controllers\Api\v1\Back\Worksheet\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\Worksheet\Action\ActionStatusRequest;
use App\Http\Requests\Worksheet\Action\StoreCommentRequest;
use App\Http\Resources\Worksheet\Action\ActionStatusResource;
use App\Http\Resources\Worksheet\Action\CommentSaveResource;
use \App\Http\Requests\Worksheet\Action\StoreActionRequest;
use App\Models\WorksheetAction;
use \App\Services\Worksheet\ActionSave;
use \App\Services\Comment\Comment;
use \App\Classes\Telegram\Notice\TelegramNotice;

class WorksheetActionController extends Controller
{
    /**
     * Создать действие в РЛ
     */
    public function store(StoreActionRequest $request, ActionSave $actionSave)
    {
        $worksheet = \App\Models\Worksheet::findOrFail($request->worksheet_id);

        if ($request->has('status')) {
            $status = $request->status;
            switch ($status) {
                case 'confirm':
                    Comment::add($worksheet->last_action, 'confirm_action');
                    break;
                case 'abort':
                    Comment::add($worksheet->last_action, 'abort_action');
                    break;
                default:
                    break;
            }
        }

        $old = implode(' ', [
            $worksheet->last_action->task->name,
            $worksheet->last_action->begin_at->format('d.m.Y'),
            '(' . $worksheet->last_action->begin_at->format('H:i') . '-' . $worksheet->last_action->end_at->format('H:i') . ')'
        ]);

        $task = \App\Models\Task::findOrFail($request->task_id);

        $worksheet->last_action->fill([
            'begin_at' => $request->begin_at,
            'end_at' => $request->end_at,
            'task_id' => $task->id,
            'status' => $task->slug == 'confirm' ? 'confirm' : 'work',
            'worksheet_id' => $worksheet->id,
            'author_id' => auth()->user()->id,
        ])->save();

        $worksheet->load('last_action');

        $worksheet->fill(['status_id' => $task->worksheet_label])->save();

        Comment::add($worksheet->last_action, 'register_action');

        Comment::text($worksheet->last_action, $request->text);

        TelegramNotice::run($worksheet)
            ->action($old)
            ->send($worksheet->executors->keyBy('id')->forget(auth()->user()->id)->pluck('id')->toArray());

        return new \App\Http\Resources\Worksheet\Action\ActionSaveResource($worksheet->last_action);
    }



    /**
     * Добавить пользовательский комментарий
     */
    public function comment(StoreCommentRequest $request, ActionSave $actionSave)
    {
        return new CommentSaveResource(Comment::text(WorksheetAction::find($request->action_id), $request->text));
    }



    /**
     * Записать комментарий для подтверждения/отмены события
     */
    public function status(ActionStatusRequest $request, ActionSave $actionSave)
    {
        $action = WorksheetAction::findOrFail($request->action_id);
        $action->fill(['status' => 'work'])->save();

        $status = $request->status;

        switch ($status) {
            case 'confirm':
                Comment::add($action, 'confirm_action');
                break;
            case 'abort':
                Comment::add($action, 'abort_action');
                break;
        }

        return new ActionStatusResource($action);
    }
}
