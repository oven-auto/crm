<?php

namespace App\Repositories\Worksheet\DTO;

use App\Classes\DTO\AbstractDTO;
use App\Models\Worksheet;

class TaskListWorksheetDTO extends AbstractDTO
{
    public function __construct(Worksheet $item)
    {
        $this->data = [
            'id'                => $item->id,
            'type'              => $item->last_action->task->name,
            'status'            => $item->last_action->statusMsg(),
            'client'            => $item->client->fullNameOrType,
            'begin_at'          => $item->last_action->begin_at->format('d.m.Y (H:i)'),
            'end_at'            => $item->last_action->end_at->format('d.m.Y (H:i)'),
            'appeal'            => $item->appeal->name,
            'comment'           => $item->last_action->last_user_comment->text,
            'author'            => $item->author->cut_name,
            'managers'          => $item->executors->map(fn ($executor) => $executor->cut_name)->toArray(),
            'worksheet_status'  => $item->status->name,
            'salon'             => $item->company->name,
            'structure'         => isset($item->structure) ? $item->structure->name : '',
            'sub_action_id'     => '',
            'reporters'         => $item->reporters->map(fn ($reporter) => $reporter->cut_name)->toArray(),
            'closed_at'         => $item->close_at ? $item->close_at->format('d.m.Y (H:i)') : '',
            'sort'              => $item->last_action->begin_at->format('YmdHi'),
            'client_id'         => $item->client->id,
        ];
    }
}
