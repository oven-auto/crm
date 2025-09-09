<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

Class WorksheetListFilter extends AbstractFilter
{
    public const SHOW = 'show';
    public const CONTROL_DATE = 'control_date';
    public const EXECUTOR_IDS = 'executor_ids';
    public const DATE_FOR_CLOSING = 'date_for_closing';
    public const OPENING = ['work'];
    public const CLOSING = ['confirm','abort','check'];
    public const INIT = 'init';

    public function __construct(array $queryParams)
    {
        $queryParams['init'] = 'init';
        parent::__construct($queryParams);
    }

    protected function getCallbacks(): array
    {
        return [
            self::INIT                  => [$this, 'init'],
            self::CONTROL_DATE          => [$this, 'controlDate'],
            self::SHOW                  => [$this, 'show'],
            self::DATE_FOR_CLOSING      => [$this, 'dateForClosing'],
            self::EXECUTOR_IDS          => [$this, 'executorIds'],
        ];
    }

    public function init(Builder $builder)
    {
        $builder
            ->select('worksheets.*')
            ->leftJoin('worksheet_actions', function($join) {
                $join->on('worksheet_actions.worksheet_id','worksheets.id');
            })
            ->groupBy('worksheets.id')->groupBy('worksheet_actions.begin_at')
            ->orderBy('worksheet_actions.begin_at');
    }

    public function dateForClosing(Builder $builder, $value)
    {
        $date = $this->formatDate($value);

        $builder->whereDate('worksheet_actions.begin_at', '=', $date);
    }

    public function show(Builder $builder, $value)
    {
        switch( $value ) {
            case 'opening':
                $builder->whereIn('worksheets.status_id', self::OPENING);
                break;

            case 'closing':
                $builder->whereIn('worksheets.status_id', self::CLOSING);
                break;
        }
    }

    public function controlDate(Builder $builder, $value)
    {
        $date = $this->formatDate($value);

        if(now()->format('Y-m-d') >= $date)
            $builder->whereDate('worksheet_actions.begin_at', '<=', $date);
        else
            $builder->whereDate('worksheet_actions.begin_at', '=', $date);
    }

    public function executorIds(Builder $builder, $value)
    {
        $builder->leftJoin('worksheet_executors','worksheet_executors.worksheet_id','worksheets.id');

        $builder->where(function($query) use ($value){
            $query->whereIn('worksheet_executors.user_id', $value);
        });
    }
}
