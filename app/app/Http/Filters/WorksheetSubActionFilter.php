<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

Class WorksheetSubActionFilter extends AbstractFilter
{
    public const SHOW = 'show';
    public const CONTROL_DATE = 'control_date';
    public const EXECUTOR_IDS = 'executor_ids';
    public const DATE_FOR_CLOSING = 'date_for_closing';

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
            ->orderBy('sub_actions.id');
    }



    public function dateForClosing(Builder $builder, $value)
    {
        $date = $this->formatDate($value);

        $builder->whereDate('sub_actions.closed_at', '=', $date);
    }



    public function show(Builder $builder, $value)
    {
        switch( $value ) {
            case 'opening':
                $builder->where('sub_actions.status', \App\Models\SubAction::STATUS_SYNONIM['work']);
                break;

            case 'closing':
                $builder->where('sub_actions.status', \App\Models\SubAction::STATUS_SYNONIM['close']);
                break;
        }
    }



    public function controlDate(Builder $builder, $value)
    {
        $date = $this->formatDate($value);

        if(now()->format('Y-m-d') >= $date)
            $builder->whereDate('sub_actions.created_at', '<=', $date);
        else
            $builder->whereDate('sub_actions.created_at', '=', $date);
    }



    public function executorIds(Builder $builder, $value)
    {
        $builder->leftJoin('sub_action_executors','sub_action_executors.sub_action_id','sub_actions.id');

        $builder->where(function($query) use ($value){
            $query->whereIn('sub_action_executors.user_id', $value);
        });
    }
}
