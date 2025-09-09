<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class ColorFilter extends AbstractFilter
{
    public const BRAND_ID   = 'brand_id';
    public const NAME       = 'name';
    public const CODE       = 'code';

    protected function getCallbacks(): array
    {
        return [
            self::BRAND_ID          => [$this, 'brandId'],
            self::NAME              => [$this, 'name'],
            self::CODE              => [$this, 'code'],
        ];
    }

    
    
    public function brandId(Builder $builder, string $value)
    {
        $builder->where('colors.brand_id',  $value);
    }
    
    

    public function name(Builder $builder, string $value)
    {
        $builder->where('colors.name', 'like', '%'. $value.'%');
    }
    
    

    public function code(Builder $builder, string $value)
    {
        $builder->where('colors.code', 'like', '%'. $value.'%');
    }
}
