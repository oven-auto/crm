<?php

namespace App\Services\Worksheet;
use App\Models\Client;
use App\Models\Interfaces\PersonInterface;
use App\Models\Task;
use App\Models\WorksheetAction;
use App\Models\Worksheet;

Class Comment
{
    public static function commentAppendClient($worksheet_id, PersonInterface $person, $message)
    {
        $comment = new Comment();
        $personName = $person->abbreviated_name();
        $action = WorksheetAction::where('worksheet_id', $worksheet_id)->orderBy('id','DESC')->first();
        if(!$action)
            $action = WorksheetAction::create([
                'worksheet_id' => $worksheet_id,
                'begin_at' => date('Y-m-d H:m:s'),
                'end_at' => date('Y-m-d H:m:s'),
                'task_id' => Task::where('slug','control')->first()->id,
                'author_id' => auth()->user()->id
            ]);
        //$comment->writeComment($action, "{$message}: {$personName}");
        $comment->addSystemComment($action->worksheet, "{$message}: {$personName}");
    }

    public function writeComment(WorksheetAction $action, $str, $status = 0)
    {
        $statusVal = $status ? (in_array($action->status, ['abort','confirm']) ? $action->status : NULL) : NULL;
        $action->last_comment()->create([
            'author_id' => auth()->user()->id,
            'text' => $str,
            'status' => $statusVal,
        ]);
    }

    public static function addComment(Worksheet $worksheet, $message)
    {
        $worksheet->last_action->last_comment()->create([
            'author_id' => auth()->user()->id,
            'text' => $message,
            'status' => NULL,
        ]);
    }

    public function addUserComment(Worksheet $worksheet, $message)
    {
        $worksheet->last_action->last_comment()->create([
            'author_id' => auth()->user()->id,
            'text' => $message,
            'status' => NULL,
            'type' => 0
        ]);
    }

    public function addSystemComment(Worksheet $worksheet, $message)
    {
        $worksheet->last_action->last_comment()->create([
            'author_id' => auth()->user()->id,
            'text' => $message,
            'status' => NULL,
            'type' => 1
        ]);
    }

    public function getAllCommentInWorksheet(array $data)
    {
        $comments = \App\Models\WorksheetActionComment::select('worksheet_action_comments.*')
            ->with(['author','action'])
            ->leftJoin('worksheet_actions', 'worksheet_actions.id', 'worksheet_action_comments.action_id')
            ->where('worksheet_actions.worksheet_id', $data['worksheet_id'])
            ->orderBy('worksheet_action_comments.id','DESC')
            ->get();

        return $comments;
    }
}
