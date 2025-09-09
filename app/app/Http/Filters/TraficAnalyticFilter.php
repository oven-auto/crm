<?php

namespace App\Http\Filters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Predis\Command\Traits\LeftRight;

Class TraficAnalyticFilter extends AbstractFilter
{
    public const INTERVAL_BEGIN = 'interval_begin';
    public const INTERVAL_END = 'interval_end';
    public const APPEAL_IDS = 'appeal_ids';
    public const AUTHOR_ID = 'author_id';
    public const MANAGER_ID = 'manager_id';
    public const COMPANY_IDS = 'company_ids';
    public const STUCTURE_IDS = 'structure_ids';
    public const CHANDEL = 'chanels';

    /*-----------------------------------------*/
    public const INIT = 'init';



    protected function getCallbacks() : array
    {
        return [
            self::INIT              => [$this, 'init'],
            self::INTERVAL_BEGIN    => [$this, 'intervalBegin'],
            self::INTERVAL_END      => [$this, 'intervalEnd'],
            self::APPEAL_IDS        => [$this, 'appealIds'],
            self::MANAGER_ID        => [$this, 'managerId'],
            self::AUTHOR_ID         => [$this, 'authorId'],
            self::COMPANY_IDS       => [$this, 'companyIds'],
            self::STUCTURE_IDS      => [$this, 'structureIds'],
            self::CHANDEL           => [$this, 'chanels'],
        ];
    }



    public function __construct($queryParams)
    {
        $queryParams['init'] = 'init';
        parent::__construct($queryParams);
    }



    public function init(Builder $builder)
    {
        $builder
            ->rightJoin('trafic_appeals', 'trafic_appeals.id', 'trafics.trafic_appeal_id')
            ->rightJoin('trafic_clients', 'trafic_clients.trafic_id', 'trafics.id')
            ->rightJoin('trafic_chanels', 'trafic_chanels.id', 'trafics.trafic_chanel_id')
            ->rightJoin('client_types', 'client_types.id', 'trafic_clients.client_type_id')
            ->leftJoin('users', 'users.id', 'trafics.author_id')
            ->leftJoin('users as managers', 'managers.id', 'trafics.manager_id')
            ->rightJoin('trafic_statuses', 'trafic_statuses.id', 'trafics.trafic_status_id')
            ->rightJoin('appeals', 'appeals.id', 'trafic_appeals.appeal_id');
    }



    public function chanels(Builder $builder, array $values)
    {
        $builder->whereIn('trafics.trafic_chanel_id', $values);
    }



    public function intervalBegin(Builder $builder, $value) : void
    {
        $carbon = Carbon::createFromFormat('Y-m-d', $this->formatDate($value));
        
        $builder->whereDate('trafics.created_at', '>=', $carbon);
    }



    public function intervalEnd(Builder $builder, $value) : void
    {
        $carbon = Carbon::createFromFormat('Y-m-d', $this->formatDate($value));
        
        $builder->whereDate('trafics.created_at', '<=', $carbon);
    }



    public function appealIds(Builder $builder, $value)
    {
        if(is_array($value))
            $builder->whereIn('trafic_appeals.appeal_id', $value);
        elseif(is_numeric($value))
            $builder->where('trafic_appeals.appeal_id', $value);
    }



    public function managerId(Builder $builder, $value)
    {
        if(is_array($value))
        {
            $builder->where(function($query) use ($value){
                $query->whereIn('trafics.manager_id', $value)
                    ->orWhereNull('trafics.manager_id');
            });

        }
        elseif(is_numeric($value))
            $builder->where('trafics.manager_id', $value);
    }



    public function authorId(Builder $builder, $value)
    {
        if(is_array($value))
            $builder->whereIn('trafics.author_id', $value);
        elseif(is_numeric($value))
            $builder->where('trafics.author_id', $value);
    }



    public function companyIds(Builder $builder, $value)
    {
        if(is_array($value))
            $builder->whereIn('trafics.company_id', $value);
        elseif(is_string($value))
            $builder->where('trafics.company_id', $value);
    }


    
    public function structureIds(Builder $builder, $value)
    {
        if(is_array($value))
            $builder->whereIn('trafics.company_structure_id', $value);
        elseif(is_string($value))
            $builder->where('trafics.company_structure_id', $value);
    }
}
