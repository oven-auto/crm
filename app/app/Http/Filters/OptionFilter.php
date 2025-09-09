<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class OptionFilter extends AbstractFilter
{
    public const BRAND_ID   = 'brand_id';
    public const MARK_ID    = 'mark_id';
    public const CODE       = 'code';
    public const NAME       = 'name';
    public const CAR_ID     = 'car_id';
    public const IDS        = 'ids';
    public const TRASH      = 'trash';

    protected function getCallbacks(): array
    {
        return [
            self::BRAND_ID          => [$this, 'brandId'],
            self::MARK_ID           => [$this, 'markId'],
            self::CODE              => [$this, 'code'],
            self::NAME              => [$this, 'name'],
            self::CAR_ID            => [$this, 'carId'],
            self::IDS               => [$this, 'ids'],
            self::TRASH             => [$this, 'trash'],
        ];
    }



    public function markId(Builder $builder, string|int $value)
    {
        $builder->where('options.mark_id',  $value);
    }



    public function brandId(Builder $builder, string|int $value)
    {
        $builder->where('options.brand_id',  $value);
    }



    public function code(Builder $builder, string $value)
    {
        $builder->where('options.code', 'LIKE', '&' . $value . '&');
    }



    public function name(Builder $builder, string $value)
    {
        $builder->where('options.name', 'LIKE', '&' . $value . '&');
    }



    public function carId(Builder $builder, string|int $value)
    {
        $builder->withTrashed();
        $builder->rightJoin('car_options', 'car_options.option_id', 'options.id')
            ->where('car_options.car_id', $value);
    }



    public function ids(Builder $builder, array $value)
    {
        $builder->whereIn('options.id',  $value);
    }



    public function trash(Builder $builder, string $value)
    {
        $builder->onlyTrashed();
    }
}
