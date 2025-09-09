<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class PackFilter extends AbstractFilter
{
    public const BRAND_ID = 'brand_id';
    public const COMPLECTATION_ID = 'complectation_id';
    public const CODE = 'code';
    public const MARK_ID = 'mark_id';

    protected function getCallbacks(): array
    {
        return [
            self::BRAND_ID          => [$this, 'brandId'],
            self::COMPLECTATION_ID  => [$this, 'complectationId'],
            self::CODE              => [$this, 'code'],
            self::MARK_ID           => [$this, 'markId'],
        ];
    }

    public function brandId(Builder $builder, $value)
    {
        $builder->where('packs.brand_id',  $value);
    }

    public function complectationId(Builder $builder, $value)
    {
        $builder
            ->rightJoin('complectation_packs', 'complectation_packs.pack_id', '=', 'packs.id')
            ->where('complectation_packs.complectation_id', $value);
    }

    public function code(Builder $builder, $value)
    {
        $builder->where('packs.code', 'like', '%'. $value.'%');
    }

    public function markId(Builder $builder, $value)
    {
        $builder
            ->leftJoin('pack_marks', 'pack_marks.pack_id', '=', 'packs.id')
            ->where('pack_marks.mark_id', $value);
    }
}
