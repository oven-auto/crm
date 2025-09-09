<?php

namespace App\Repositories\Worksheet\DTO;

use App\Classes\DTO\AbstractDTO;
use App\Models\Trafic;
use App\Models\Worksheet;
use App\Models\Task;

class WorksheetActionCreateDTO extends AbstractDTO
{
    public function __construct(Trafic $trafic, Worksheet $worksheet)
    {
        $this->data = [
            'task_id' => Task::where('slug', 'control')->first()->id,
            'worksheet_id' => $worksheet->id,
            'begin_at' => $trafic->begin_at,
            'end_at' => $trafic->end_at,
            'author_id' => auth()->user()->id,
        ];
    }
}
