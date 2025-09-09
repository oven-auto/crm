<?php

namespace App\Http\Filters;

use App\Models\WsmReserveNewCar;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

Class DiscountFilter extends AbstractFilter
{
    public const IDS = 'ids';
    public const TYPE = 'type';
    public const INPUT = 'input';
    public const INIT = 'init';
    public const RETURNED = 'returned';
    public const CHECKED = 'checked';
    public const BRAND_ID = 'brand_id';
    public const MARK_ID = 'mark_id';
    public const IS_BASE = 'isbase';
    public const IS_REPARATION = 'isreparation';
    public const SALE_INTERVAL = 'sale_interval';
    public const FACT_INTERVAL = 'fact_interval';
    public const HAS_SALE   = 'has_sale';



    public function __construct($queryParams)
    {
        $queryParams['init'] = 'init';
        parent::__construct($queryParams);
    }



    public function init(Builder $builder)
    {
        $builder
            ->leftJoin('discount_sums', 'discount_sums.discount_id', 'discounts.id')
            ->leftJoin('discount_reparations', 'discount_reparations.discount_id', 'discounts.id')
            ->leftJoin('discount_reparation_dates', 'discount_reparation_dates.discount_id', 'discounts.id')
            ->leftJoin('discount_bases', 'discount_bases.discount_id', 'discounts.id')
            ->leftJoin('discount_types', 'discount_types.id', 'discounts.discount_type_id')
            ->leftJoin('disckount_checks', 'disckount_checks.discount_id', 'discounts.id')
            ->leftJoin('worksheets', 'worksheets.id', 'discounts.worksheet_id')
            ->leftJoin('clients', 'clients.id', 'worksheets.client_id')
            ->leftJoin('wsm_reserve_new_cars', function($join){
                $join->on('wsm_reserve_new_cars.id', 'discounts.modulable_id');
            })
            ->leftJoin('cars', 'cars.id', 'wsm_reserve_new_cars.car_id')
            ->leftJoin('wsm_reserve_sales', 'wsm_reserve_sales.reserve_id', 'wsm_reserve_new_cars.id')
            ->where(function($query){
                $query->orWhere(function($queryReserve){
                    $queryReserve->where('discounts.modulable_id', DB::raw('wsm_reserve_new_cars.id'))
                        ->where('discounts.modulable_type', 'App\\Models\\WsmReserveNewCar');
                });
            })
            ->whereNull('wsm_reserve_new_cars.deleted_at');
    }



    public function getCallbacks() : array
    {
        return [
            self::INIT              => [$this, 'init'],
            self::IDS               => [$this, 'ids'],
            self::TYPE              => [$this, 'type'],
            self::INPUT             => [$this, 'input'],
            self::RETURNED          => [$this, 'returned'],
            self::CHECKED           => [$this, 'checked'],
            self::BRAND_ID          => [$this, 'brand_id'],
            self::MARK_ID           => [$this, 'mark_id'],
            self::IS_BASE           => [$this, 'isbase'],
            self::IS_REPARATION     => [$this, 'isreparation'],
            self::SALE_INTERVAL     => [$this, 'sale_interval'],
            self::FACT_INTERVAL     => [$this, 'fact_interval'],
        ];
    }



    public function hasSale(Builder $builder, bool $val)
    {
        match($val) {
            true => $builder->whereNotNull('wsm_reserve_sales.id'),
            false => $builder->whereNull('wsm_reserve_sales.id'),
            default => ''
        };
    }



    /**
     * Получить все скидки на автомобилях проданных в указанный период
     */
    public function sale_interval(Builder $builder, array $value)
    {
        $date_1 = Carbon::createFromFormat('d.m.Y', $value[0])->format('Y-m-d');
        $date_2 = isset($value[1]) ? Carbon::createFromFormat('d.m.Y', $value[1])->format('Y-m-d') : $date_1;

        $builder->whereBetween('wsm_reserve_sales.date_at', [$date_1, $date_2]);
    }



    /**
     * Получить все скидки с датой возмещения за указанный период
     */
    public function fact_interval(Builder $builder, array $value)
    {   
        $date_1 = Carbon::createFromFormat('d.m.Y', $value[0])->format('Y-m-d');
        $date_2 = isset($value[1]) ? Carbon::createFromFormat('d.m.Y', $value[1])->format('Y-m-d') : $date_1;
   
        $builder->whereBetween('discount_reparation_dates.date_at', [$date_1, $date_2]);
    }



    /**
     * Получить скидки по оснаванию
     * yes - есть основание
     * no - нет основания
     */
    public function isbase(Builder $builder, $value)
    {
        match($value) {
            'yes' => $builder->whereNotNull('discount_bases.id'),
            'no' => $builder->whereNull('discount_bases.id'),
            default => ''
        };
    }



    /**
     * Получить скидки по возмещению
     * yes - есть возмещению
     * no - нет возмещению
     */
    public function isreparation(Builder $builder, $value)
    {
        match($value) {
            'yes' => $builder->whereNotNull('discount_reparation_dates.id'),
            'no' => $builder->whereNull('discount_reparation_dates.id'),
            default => ''
        };
    }



    /**
     * Получить скидки по тому возвращаемые они или нет:
     * yes = возвращаемая
     * no = не возвращаемая
     */
    public function returned(Builder $builder, string $value)
    {
        match($value){
            'yes' => $builder->where('discount_types.returnable', 1),
            'no' => $builder->where('discount_types.returnable', 0),
            default => ''
        };
    }



    /**
     * Получить скидки по тому проверены они или нет:
     * yes = проверена
     * no = не проверена
     */
    public function checked(Builder $builder, string $value)
    {
        match($value){
            'yes' => $builder->where('disckount_checks.status', 1),
            'no' => $builder->where('disckount_checks.status', 0),
            default => ''
        };
    }



    /**
     * Вернуть скидки для автомобилей казанных брендов
     */
    public function brand_id(Builder $builder, array $value)
    {
        $builder->whereIn('cars.brand_id', $value);
    }



    /**
     * Вернуть скидки для автомобилей казанных моделей
     */
    public function mark_id(Builder $builder, array $value)
    {
        $builder->whereIn('cars.mark_id', $value);
    }



    /**
     * Получить скидки по указанным ID
     */
    public function ids(Builder $builder, array $array)
    {
        $builder->whereIn('discounts.id', $array);
    }



    /**
     * Получить скидки по типу скидки
     */
    public function type(Builder $builder, array $value)
    {
        $builder->whereIn('discounts.discount_type_id', $value);
    }



    /**
     * Input
     */
    public function input(Builder $builder, $value)
    {
        $builder->where(function($query) use ($value){
            $query->orWhere('clients.lastname', 'LIKE', '%'.$value.'%');
            $query->orWhere('cars.vin', 'LIKE', '%'.$value.'%');
            $query->orWhere('clients.company_name', 'LIKE', '%'.$value.'%');
        });
    }
}