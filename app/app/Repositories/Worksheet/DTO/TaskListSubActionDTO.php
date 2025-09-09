<?php

namespace App\Repositories\Worksheet\DTO;

use App\Classes\DTO\AbstractDTO;
use App\Models\SubAction;

class TaskListSubActionDTO extends AbstractDTO
{
    public function __construct(SubAction $item)
    {
        $this->data = [
            'id'                => $item->worksheet_id,
            'type'              => $item->title,
            'status'            => SubAction::STATUSES[$item->status],
            'client'            => $item->worksheet->client->full_name,
            'begin_at'          => $item->created_at->format('d.m.Y (H:i)'),
            'end_at'            => $item->created_at->addMinutes($item->duration)->format('d.m.Y (H:i)'),
            'appeal'            => '',
            'comment'           => '',
            'author'            => $item->author->cut_name,
            'managers'          => $item->executors->map(fn ($executor) => $executor->cut_name)->toArray(),
            'worksheet_status'  => '',
            'salon'             => '',
            'structure'         => '',
            'sub_action_id'     => $item->id,
            'reporters'         => $item->reporters->map(fn ($reporter) => $reporter->cut_name)->toArray(),
            'closed_at'         => $item->closed_at ? $item->closed_at->format('d.m.Y (H:i)') : '',
            'sort'              => $item->created_at->format('YmdHi'),
            'client_id'         => $item->worksheet->client->id,
        ];
    }
}
