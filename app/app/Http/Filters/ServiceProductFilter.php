<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

Class ServiceProductFilter extends AbstractFilter
{
    public const INPUT          = 'input';
    public const APPEALS        = 'appeal_ids';
    public const GROUPS         = 'group_ids';
    public const INIT           = 'init';



    public function __construct(array $queryParams)
    {
        $queryParams['init'] = 'init';

        parent::__construct($queryParams);
    }



    protected function getCallbacks(): array
    {
        return [
            self::INIT              => [$this, 'init'],
            self::INPUT             => [$this, 'fnInput'],
            self::APPEALS           => [$this, 'fnAppeals'],
            self::GROUPS            => [$this, 'fnGroups'],
        ];
    }



    public function init(Builder $builder, $val)
    {
        $builder->leftJoin('service_product_appeals as sp_appeal', 'sp_appeal.service_product_id', 'service_products.id')
            ->leftJoin('appeals','appeals.id','sp_appeal.appeal_id');
    }



    public function fnInput(Builder $builder, string $val)
    {
        $builder
            ->where('appeals.name', 'LIKE', "%{$val}%")
            ->orWhere('service_products.name','LIKE', "%{$val}%");   
    }



    public function fnAppeals(Builder $builder, array $val)
    {
        $builder->whereIn('sp_appeal.appeal_id', $val);
    }



    public function fnGroups(Builder $builder, array $val)
    {
        $builder->whereIn('service_products.group_id', $val);
    }
}