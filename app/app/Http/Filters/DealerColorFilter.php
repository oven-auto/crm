<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class DealerColorFilter extends AbstractFilter
{
    public const BRAND_ID   = 'brand_id';
    public const MARK_ID    = 'mark_id';
    public const NAME       = 'name';
    public const TRASH      = 'trash';


    protected function getCallbacks(): array
    {
        return [
            self::BRAND_ID          => [$this, 'brandId'],
            self::MARK_ID           => [$this, 'markId'],
            self::NAME              => [$this, 'name'],
            self::TRASH             => [$this, 'trash'],
        ];
    }



    public function brandId(Builder $builder, string $value)
    {
        $builder->where('dealer_colors.mark_id',  $value);
    }



    public function trash(Builder $builder, bool $value)
    {
        match($value){
            true => $builder->onlyTrashed(),
            default => null
        };
    }

    
    
    public function markId(Builder $builder, string $value)
    {
        $builder->where('dealer_colors.mark_id',  $value);
    }
    
    

    public function name(Builder $builder, string $value)
    {
        $builder->where('dealer_colors.name', 'like', '%'. $value.'%');
    }
}