<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

Class NewWorksheetAnalyticFilter extends AbstractFilter
{
    public const INIT = 'init';
    public const INTERVAL_BEGIN = 'interval_begin';
    public const INTERVAL_END = 'interval_end';
    public const APPEAL_IDS = 'appeal_ids';
    public const AUTHOR_ID = 'author_id';
    public const EXECUTOR_ID = 'manager_id';
    public const CREATED_BEGIN = 'created_begin';
    public const CREATED_END = 'created_end';
    public const CLOSED_BEGIN = 'closed_begin';
    public const CLOSED_END = 'closed_end';
    public const COMPANY_IDS = 'company_ids';
    public const STRUCTURE_IDS = 'structure_ids';
    public const CHANEL = 'chanels';

    public static $GroupByWorkshhetId = 1;

    public function __construct($queryParams)
    {
        $queryParams['init'] = 'init';
        parent::__construct($queryParams);
    }

    protected function getCallbacks() : array
    {
        return [
            self::INIT                  => [$this, 'init'],
            self::INTERVAL_BEGIN        => [$this,'intervalBegin'],
            self::INTERVAL_END          => [$this,'intervalEnd'],
            self::APPEAL_IDS            => [$this,'appealIds'],
            self::EXECUTOR_ID           => [$this,'executorIds'],
            self::AUTHOR_ID             => [$this,'authorId'],

            self::CREATED_BEGIN         => [$this,'createdBegin'],
            self::CREATED_END           => [$this,'createdEnd'],

            self::CLOSED_BEGIN          => [$this,'closedBegin'],
            self::CLOSED_END            => [$this,'closedEnd'],

            self::STRUCTURE_IDS         => [$this, 'structureIds'],
            self::COMPANY_IDS           => [$this, 'companyIds'],
            self::CHANEL                => [$this, 'chanels'],
        ];
    }

    public function init(Builder $builder)
    {
        $builder
            ->leftJoin('worksheet_actions', 'worksheet_actions.worksheet_id','worksheets.id')
            ->leftJoin('trafics', 'trafics.id', 'worksheets.trafic_id')
            ->leftJoin('trafic_clients', 'trafic_clients.trafic_id', 'trafics.id')
            ->leftJoin('tasks', 'tasks.id', 'worksheet_actions.task_id')
            ->leftJoin('client_types', 'client_types.id', 'trafic_clients.client_type_id')
            ->leftJoin('users', 'users.id', 'worksheets.author_id');
    }



    public function chanels(Builder $builder, array $values)
    {
        $builder->whereIn('trafics.trafic_chanel_id', $values);
    }



    public function createdBegin(Builder $builder, $value)
    {
        $builder->whereDate('worksheets.created_at', '>=', $this->formatDate($value));
    }



    public function createdEnd(Builder $builder, $value)
    {
        $builder->whereDate('worksheets.created_at', '<=', $this->formatDate($value));
    }



    public function closedBegin(Builder $builder, $value)
    {
        $builder->whereIn('worksheet_actions.status', ['confirm','abort'])
            ->whereDate('worksheet_actions.updated_at', '>=', $this->formatDate($value));
    }



    public function closedEnd(Builder $builder, $value)
    {
        $builder->whereDate('worksheet_actions.updated_at', '<=', $this->formatDate($value));
    }



    public function intervalBegin(Builder $builder, $value)
    {

    }



    public function intervalEnd(Builder $builder, $value)
    {

    }



    public function appealIds(Builder $builder, $value)
    {
        if(is_array($value))
            $builder->whereIn('worksheets.appeal_id', $value);
        elseif(is_numeric($value) || is_string($value))
            $builder->where('worksheets.appeal_id', $value);
    }



    public function authorId(Builder $builder, $value)
    {
        if(is_array($value))
            $builder->whereIn('worksheets.author_id', $value);
        elseif(is_numeric($value) || is_string($value))
            $builder->where('worksheets.author_id', $value);
    }



    public function executorIds(Builder $builder, $value)
    {
        $builder->leftJoin('worksheet_executors','worksheet_executors.worksheet_id','worksheets.id');

        if(is_array($value))
            $builder->whereIn('worksheet_executors.user_id', $value);
        elseif(is_numeric($value) || is_string($value))
            $builder->where('worksheet_executors.user_id', $value);
    }



    public function companyIds(Builder $builder, $value)
    {
        $builder->whereIn('worksheets.company_id', $value);
    }



    public function structureIds(Builder $builder, $value)
    {
        // $builder->leftJoin('company_structures', function($join){
        //     $join->on('company_structures.structure_id', 'worksheets.structure_id');
        //     $join->on('company_structures.company_id', 'worksheets.company_id');
        // });

        // $builder->whereIn('company_structures.id', $value);
        $builder->whereIn('worksheets.structure_id', $value);
    }
}
