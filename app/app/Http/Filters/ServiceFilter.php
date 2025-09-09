<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class ServiceFilter extends AbstractFilter
{
    public const TRASH = 'trash';
    public const MODULE = 'module_id';
    public const CATEGORY = 'category_id';
    public const INIT = 'init';



    public function __construct(array $queryParams)
    {
        $queryParams['init'] = 'init';

        parent::__construct($queryParams);
    }



    protected function getCallbacks() : array
    {
        return [
            self::TRASH         => [$this, 'fnTrash'],
            self::MODULE        => [$this, 'fnModule'],
            self::CATEGORY      => [$this, 'fnCategory'],
            self::INIT          => [$this, 'init'],
        ];
    }



    public function init(Builder $builder, )
    {
        $builder->leftJoin('service_applicabilities', 'service_applicabilities.service_id', 'services.id');

        $builder->groupBy('services.id');
    }



    public function fnModule(Builder $builder, int $val)
    {
        $builder->where('service_applicabilities.module_id', $val);
    }



    public function fnCategory(Builder $builder, int $val)
    {
        $builder->where('services.category_id', $val);
    }



    public function fnTrash(Builder $builder, bool $val)
    {
        match($val){
            true => $builder->onlyTrashed(),
            default => '',
        };
    }
}