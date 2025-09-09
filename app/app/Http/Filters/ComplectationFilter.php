<?php

namespace App\Http\Filters;

use App\Models\Car;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Schema(
 *   description = "Параметры фильтрации комплектаций"
 * )
 */
class ComplectationFilter extends AbstractFilter
{
    /**  @OA\Property(
     *      format="array",
     *      description="Массив содержащий идентификаторы бренда",
     *      property="brands",
     *      type="array",
     *      example="[1,2]",
     *      @OA\Items(
     *      )
     * )
     * */
    public const BRAND_ID   = 'brand_id';
    
    /**  @OA\Property(
     * 		format="string",
     * 		description="Полнотекстовый поиск, названия комплектации.",
     * 		property="name",
     * 		type="string",
     * 		example="1122")
     * */
    public const NAME       = 'name';
    
    /**  @OA\Property(
     * 		format="string",
     * 		description="Полнотекстовый поиск, кода комплектации.",
     * 		property="code",
     * 		type="string",
     * 		example="1122")
     * */
    public const CODE       = 'code';
    
    /**  @OA\Property(
     *      format="array",
     *      description="Массив содержащий идентификаторы выбранных моделей",
     *      property="mark_id",
     *      type="array",
     *      example="[1,2]",
     *      @OA\Items(
     *      )
     * )
     * */
    public const MARK_ID    = 'mark_id';
    
    /**  @OA\Property(
     * 		format="string",
     * 		description="Поиск комплектаций по статусу активности <all|trash|active>.",
     * 		property="status",
     * 		type="string",
     * 		example="1122")
     * */
    public const STATUS     = 'status';
    
    /**  @OA\Property(
     *      format="array",
     *      description="Массив содержащий идентификаторы выбранных комплектаций",
     *      property="ids",
     *      type="array",
     *      example="[1,2]",
     *      @OA\Items(
     *      )
     * )
     * */
    public const IDS        = 'ids';
    
    /**  @OA\Property(
     * 		format="string",
     * 		description="Поиск только удаленных комплектаций.",
     * 		property="trash",
     * 		type="string",
     * 		example="1122")
     * */
    public const TRASH      = 'trash';
    
    /**  @OA\Property(
     * 		format="string",
     * 		description="Поиск комплектаций по статусу наличия машин в продаже <sold|sale>.",
     * 		property="insale",
     * 		type="string",
     * 		example="1122")
     * */
    public const INSALE     = 'insale';
    
    /**  @OA\Property(
     * 		format="string",
     * 		description="Поиск комплектаций по статусу проверки <totrash|towork|tochange>.",
     * 		property="action",
     * 		type="string",
     * 		example="1122")
     * */
    public const ACTION     = 'action';
    
    /**  @OA\Property(
     * 		format="string",
     * 		description="Полнотекстовый поиск, кода или названия комплектации.",
     * 		property="input",
     * 		type="string",
     * 		example="1122")
     * */
    public const INPUT      = 'input';
    
    public const INIT = 'init';

    protected function getCallbacks(): array
    {
        return [
            self::IDS               => [$this, 'ids'],
            self::INIT              => [$this, 'init'],
            self::BRAND_ID          => [$this, 'brandId'],
            self::NAME              => [$this, 'name'],
            self::CODE              => [$this, 'code'],
            self::MARK_ID           => [$this, 'markId'],
            self::TRASH             => [$this, 'trash'],
            self::STATUS            => [$this, 'status'],
            self::INSALE            => [$this, 'inSale'],
            self::ACTION            => [$this, 'action'],
            self::INPUT             => [$this, 'input'],
        ];
    }



    public function __construct(array $queryParams)
    {
        $queryParams['init'] = 'init';
        $queryParams['status'] = $queryParams['status'] ?? 0;
        parent::__construct($queryParams);
    }



    public function init(Builder $builder) : void
    {
        $builder->leftJoin('marks', 'marks.id', 'complectations.mark_id');
        $builder
            ->saledCountCars()
            ->activeCountCars();
        $builder->leftJoin('complectation_current_prices', 'complectation_current_prices.complectation_id', 'complectations.id');
        $builder->groupBy('complectations.id');
    }



    /**
     * Получить те которые подходят под поиск из input
     */
    public function input(Builder $builder, string $value) : void
    {
        $builder->where(function($query) use($value){
            $query->where('complectations.code', 'LIKE', '%'.$value.'%');
            $query->orWhere('complectations.name', 'LIKE', '%'.$value.'%');
        });
    }



    /**
     * Получить только те которые нужно проверить
     */
    public function action(Builder $builder, $value) : void
    {
        match ($value){
            'totrash'   => $builder->withoutTrashed()->havingRaw(('max(active_car) < 1')),
            'towork'    => $builder->onlyTrashed()->havingRaw(('max(active_car) > 0')),
            'tochange'  => $builder
                ->havingRaw(('max(active_car) > 0'))
                ->withoutTrashed()
                ->where('complectation_current_prices.begin_at', '<', DB::raw('(SELECT max(begin_at) FROM complectation_prices)')),
            default => '',
        };
    }



    /**
     * Получить только те комплектации, которые: 
     * 1 - имеют хотя бы одну проданную машину <sold>
     * 2 - имеют хотя бы одну продающуюся машину <sale>
     */
    public function inSale(Builder $builder, string $value) : void
    {
        match($value) {
            'sold' => $builder->havingRaw(('max(saled_cars) > 0')),
            'sale' => $builder->havingRaw(('max(active_car) > 0')),
            default => ''
        };
    }



    /**
     * Показать только те комплектации, которые:
     * 1 - в архиве <trash>
     * 2 - активные <active>
     * 3 - все <all|empty>
     */
    public function status(Builder $builder, string $value) : void
    {
        match($value){
            'all' => $builder->withTrashed(),
            'trash' => $builder->onlyTrashed(),
            'active' => $builder->withoutTrashed(),
            default => $builder->withTrashed()
        };
    }



    /**
     * Показать только удаленные комплектции
     */
    public function trash(Builder $builder, int|string $value) : void
    {
        $builder->onlyTrashed();
    }



    /**
     * Показать только те комплектации, которые имеют указанные ID
     */
    public function ids(Builder $builder, array $data) : void
    {
        $builder->whereIn('complectations.id', $data);
    }



    /**
     * Показать только те комплектации определенного бренда
     */
    public function brandId(Builder $builder, array $value) :void
    {
        $builder->whereIn('marks.brand_id',  $value);
    }



    /**
     * Показать только те комплектации определенной модели
     */
    public function markId(Builder $builder, string|array $value) : void
    {
        if(is_array($value))
            $builder->whereIn('marks.id',  $value);
        else
            $builder->where('marks.id',  $value);
    }



    /**
     * Показать только те комплектации, которые в реквезите CODE содержат строку
     */
    public function code(Builder $builder, string $value) : void
    {
        $builder->where('complectations.code', 'like', '%'. $value.'%');
    }



    /**
     * Показать только те комплектации, которые в реквезите NAME содержат строку
     */
    public function name(Builder $builder, string $value) : void
    {
        $builder->where('complectations.name', 'like', '%'. $value.'%');
    }
}
