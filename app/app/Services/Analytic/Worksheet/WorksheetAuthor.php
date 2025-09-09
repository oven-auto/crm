<?php

namespace App\Services\Analytic\Worksheet;

use App\Http\Filters\WorksheetAnalyticFilter;
use App\Models\Worksheet;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

Class WorksheetAuthor
{
    public static function getCountAnalyticByAuthor($dataFilter)
    {
        \App\Http\Filters\WorksheetAnalyticFilter::$GroupByWorkshhetId = 0;
        $filter = app()->make(\App\Http\Filters\WorksheetAnalyticFilter::class, ['queryParams' => array_filter($dataFilter)]);

        $count = Worksheet::select([DB::raw('COUNT(worksheets.id) as count'),])
            ->where('worksheets.status_id','work')
            ->filter($filter)
            ->groupBy('worksheets.id')
            ->get()
            ->count();

        $query = Worksheet::query()
            ->select([
                DB::raw("CONCAT(users.lastname, ' ', users.name) as name"),
                DB::raw("COUNT(worksheets.id) as count")
            ])
            ->leftJoin('users', 'users.id', 'worksheets.author_id')
            ->filter($filter);

        if(collect($query->getQuery()->joins)->pluck('table')->contains('worksheet_executors'))
            $query->whereRaw(DB::raw('users.id = worksheet_executors.user_id'));

        $query->where('worksheets.status_id', 'work');

        $query->groupBy('worksheets.author_id');

        $result = $query->get()->map(function($item) use ($count){
            return [
                'name' => $item['name'],
                'count' => $item['count'],
                'percent' => number_format( (100 / $count * $item['count']), 1, '.', ''),
            ];
        });

        return $result;
    }

    public static function getCount1(array $data)
    {
        $filter = app()->make(\App\Http\Filters\WorksheetAnalyticFilter::class, ['queryParams' => array_filter($data)]);

        $count = Worksheet::select([DB::raw('COUNT(worksheets.id) as count'),])
            ->where('worksheets.status_id','work')
            ->filter($filter)
            ->groupBy('worksheets.id')
            ->get()
            ->count();

        return $count;
    }



    public static function getCount(array $data)
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
        
        $res = $query->get();

        $total = $res->sum('count');

        $arr = collect([
            [
                'count' => $total,
                'procent' => 100,
                'name' => 'Всего',
            ],
        ]);

        $arr = $arr->merge($res->map(fn($item, $k) => [
            'procent' => $total ? round(100/$total*($item->count ?? 0), 2) : 0,
            'count' => $item->count ?? 0,
            'name' => $item->name,
        ]));

        return $arr;
    }
}
