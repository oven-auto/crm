<?php

namespace App\Services\Worksheet;

use App\Models\Worksheet;

Class WorksheetCommentOnShow
{
    public function addShowComment(Worksheet $worksheet)
    {
        $commentService = new Comment();
        $commentService->addSystemComment($worksheet, "Рабочий лист просматривался пользователем ".auth()->user()->cut_name);
        //$old = $worksheet->last_action->last_comment;
        //$worksheet->last_action->last_comment->delete();
        //Comment::addComment($worksheet, "Рабочий лист просматривался пользователем ".auth()->user()->cut_name);
        //$worksheet->last_action->load('last_comment');
        //$newId = $worksheet->last_action->last_comment->id;
        //$worksheet->last_action->last_comment->fill(['id' => $old->id])->save();
        //$old->fill(['id' => $newId])->save();
        //$worksheet->last_action->load('last_comment');
    }

    public static function addShowCommentStatic(Worksheet $worksheet)
    {
        $me = new WorksheetCommentOnShow();
        $me->addShowComment($worksheet);
    }
}
