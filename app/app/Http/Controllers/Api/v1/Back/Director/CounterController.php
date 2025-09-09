<?php

namespace App\Http\Controllers\Api\v1\Back\Director;

use App\Http\Controllers\Controller;
use App\Http\Filters\TraficAnalyticFilter;
use App\Http\Filters\WorksheetAnalyticFilter;
use App\Http\Filters\WSMRedemptionCarFilter;
use App\Models\Trafic;
use App\Models\Worksheet;
use Illuminate\Http\Request;

/**
 * КОНТРОЛЬНЫЕ ПАКАЗАТЕЛИ
 */
class CounterController extends Controller
{
    public function __invoke(Request $request)
    {
        $nonIntervalArray = $request->except([
            'interval_begin', 'interval_end',
            'second_interval_begin', 'second_interval_end',
            'third_interval_begin', 'third_interval_end',
        ]);

        $queryParams = ['queryParams' => array_filter($nonIntervalArray)];

        $filterTrafic       = app()->make(TraficAnalyticFilter::class, $queryParams);
        $filterWorkSheet    = app()->make(WorksheetAnalyticFilter::class, $queryParams);
        $filterRedemptionControl   = app()->make(WSMRedemptionCarFilter::class, ['queryParams' => ['status' => 'control']]);
        $filterRedemptionWait   = app()->make(WSMRedemptionCarFilter::class, ['queryParams' => ['status' => 'wait']]);
        
        $trafic = Trafic::select('trafics.id')->whereIn('trafics.trafic_status_id', [1, 2])->filter($filterTrafic)->pluck('id');
        $worksheetOverdue = Worksheet::select('worksheets.id')->overdue()->filter($filterWorkSheet)->pluck('id');
        $worksheetCheck = Worksheet::select(['worksheets.id'])->check()->filter($filterWorkSheet)->pluck('id');
        $redemptionControl = \App\Models\WSMRedemptionCar::select('wsm_redemption_cars.id')->filter($filterRedemptionControl)->pluck('id');


        $redemptionWait = \App\Models\WSMRedemptionCar::select('wsm_redemption_cars.id')->filter($filterRedemptionWait)->pluck('id');

        $counter = [
            0 => [
                'name' => 'Необработанные обращения',
                'count' => $trafic->count(),
                'ids' => $trafic,
                'type' => 'trafics'
            ],

            1 => [
                'name' => 'Просроченные переговоры',
                'count' => $worksheetOverdue->count(),
                'ids' => $worksheetOverdue,
                'type' => 'events'
            ],

            2 => [
                'name' => 'Рабочие листы на проверке',
                'count' => $worksheetCheck->count(),
                'ids' => $worksheetCheck,
                'type' => 'worksheets'
            ],

            3 => [
                'name' => 'Оценка в закрытом Рабочем листе',
                'count' => $redemptionControl->count(),
                'ids' => $redemptionControl,
                'type' => 'appraisals'
            ],

            4 => [
                'name' => 'Ожидающие оценки',
                'count' => $redemptionWait->count(),
                'ids' => $redemptionWait,
                'type' => 'appraisals'
            ],
        ];

        return response()->json([
            'data' => $counter,
            'success' => 1
        ]);
    }
}
