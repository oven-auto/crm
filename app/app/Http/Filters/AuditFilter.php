<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

Class AuditFilter extends AbstractFilter
{
    public const IDS = 'ids';
    public const TRASH = 'trash';
    public const CHANELS = 'chanels';
    public const APPEALS = 'appeals';

    public const INIT = 'init';
    
    public function getCallbacks(): array
    {
        return [
            self::IDS       => [$this, 'ids'],
            self::TRASH     => [$this, 'trash'],
            self::CHANELS   => [$this, 'chanels'],
            self::APPEALS   => [$this, 'appeals'],

            self::INIT      => [$this, 'init'],
        ];
    }



    public function __construct(array $queryParams)
    {
        $queryParams['init'] = 'init';
        parent::__construct($queryParams);
    }
    
    
    
    public function init(Builder $builder)
    {
        $builder->leftJoin('audit_chanels', 'audit_chanels.audit_id','audits.id');
    }



    public function appeals(Builder $builder, array $val)
    {
        $builder->whereIn('audits.appeal_id', $val);
    }



    public function ids(Builder $builder, array $val)
    {
        $builder->whereIn('audits.id', $val);
    }



    public function trash(Builder $builder, bool $val)
    {
        match($val){
            true => $builder->onlyTrashed(),
            default => null
        };
    }



    public function chanels(Builder $builder, array $val)
    {
        $builder->whereIn('audit_chanels.chanel_id', $val);
    }
}