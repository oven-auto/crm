<?php

namespace App\Http\Filters;

use App\Helpers\Date\DateHelper;
use Illuminate\Database\Eloquent\Builder;

/**
 * Фильтр коммуникаций для вывода в журнал задач
 */
Class ClientEventListFilter extends AbstractFilter
{
    public const CONTROL_DATE = 'control_date';
    public const SHOW       = 'show';
    public const DATE_FOR_CLOSING = 'date_for_closing';
    public const EXECUTOR_IDS = 'executor_ids';
    public const INIT = 'init';
    public const OPENING = ['waiting'];
    public const CLOSING = ['processed'];

    protected function getCallbacks() : array
    {
        return [
            self::INIT                  => [$this, 'init'],
            self::CONTROL_DATE          => [$this, 'controlDate'],
            self::SHOW                  => [$this, 'show'],
            self::DATE_FOR_CLOSING      => [$this, 'dateForClosing'],
            self::EXECUTOR_IDS          => [$this, 'executorIds'],
        ];
    }

    public function __construct($queryParams)
    {
        $queryParams['init'] = 'init';
        parent::__construct($queryParams);
    }



    public function init(Builder $builder)
    {
        $builder->leftJoin('client_events', 'client_events.id','client_event_statuses.event_id');

        $builder->leftJoin('client_event_status_executors','client_event_status_executors.client_event_status_id','client_event_statuses.id');

        $builder->groupBy('client_event_statuses.id');
    }



    public function dateForClosing(Builder $builder, $value)
    {
        $date = $this->formatDate($value);

        $builder->whereDate('client_event_statuses.processed_at', '=', $date);
    }



    public function controlDate(Builder $builder, $value)
    {
        $date = DateHelper::createFromString($value, 'd.m.Y');
        
        if($date <= now())
            $builder->whereDate('client_event_statuses.date_at', '<=', $date);
        else
            $builder->whereDate('client_event_statuses.date_at', '=', $date);
    }



    public function show(Builder $builder, $value)
    {
        if($value == 'closing')
            $builder->whereIn('client_event_statuses.confirm', self::CLOSING);
        elseif($value == 'opening')
            $builder->where('client_event_statuses.confirm', self::OPENING);

    }



    public function executorIds(Builder $builder, Array $value)
    {
        $builder->where(function($query) use ($value){
            $query->whereIn('client_event_status_executors.user_id', $value);
            //$query->orWhereIn('client_events.author_id', $value);
        });


        //$builder->groupBy('client_event_status_executors.user_id');
    }
}
