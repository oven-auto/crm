<?php

namespace App\Services\Analytic\Worksheet;

use App\Http\Filters\WorksheetAnalyticFilter;
use App\Models\Task;
use App\Models\Worksheet;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Services\Analytic\Worksheet\Interfaces\WorksheetAnalyticInterface;

Class WorkingWorksheetAnalytic implements WorksheetAnalyticInterface
{
    public function getArrayAnalytic(array $data)
    {
        $arr = Arr::except($data, ['interval_begin','interval_end']);

        $query = Worksheet::select([
            DB::raw('client_types.name as name'),
            DB::raw('COUNT(worksheets.id) as count'),          
        ]);

        $filter = app()->make(WorksheetAnalyticFilter::class, ['queryParams' => array_filter($arr)]);
        
        $query->filter($filter);

        $query->leftJoin('client_types', 'client_types.id', 'trafic_clients.client_type_id');

        $query->where('worksheets.status_id', 'work');
        
        $query->groupBy('client_types.id');

        return $query->get()->map(fn($item, $k) => [
            'count' => $item->count ?? 0,
            'name' => $item->name,
            'total' => $item->count ?? 0,
            'percent' => $item->count ? round((100 / $item->count) * $item->count, 2) : 0,
            'type' => '',
        ]);
    }
}











// select * from `tasks`

// left join (
//     select
//         `worksheet_actions`.`task_id` as `task_id`,
//         COUNT(worksheet_actions.task_id) as count,
//         (
//             select count(*) from `worksheets`
//             where `worksheets`.`appeal_id` in (2, 3)
//             and date(`worksheets`.`created_at`) >= 2023-10-01
//             and date(`worksheets`.`created_at`) <= 2023-10-31
//         ) as `total`
//     from `worksheets`
//     left join `worksheet_actions` on `worksheet_actions`.`task_id` = `tasks`.`id`
//     where `worksheet_actions`.`id` = (
//         SELECT max(SWA.id) FROM worksheet_actions as SWA
//         WHERE SWA.worksheet_id = worksheets.id
//     ) and `worksheet_actions`.`task_id` in (6, 7) and `worksheets`.`appeal_id` in (2, 3)
//     and date(`worksheets`.`created_at`) >= 2023-10-01 and date(`worksheets`.`created_at`) <= 2023-10-31) as `subQuery`
// on `subQuery`.`task_id` = `tasks`.`id`
