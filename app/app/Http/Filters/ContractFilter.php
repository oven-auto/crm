<?php

namespace App\Http\Filters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Schema(
 *   description = "Параметры фильтрации контрактов"
 * )
 */
class ContractFilter extends AbstractFilter
{
    public const IDS            = 'ids';

    public const INIT           = 'init';

    /**  @OA\Property(
     * format="bool", 
     * description="Просроченные, 1 - есть, 0 нет.", 
     * property="overdue", 
     * type="bool", 
     * example="1")
     * */
    public const OVERDUE        = 'overdue';

    /**  @OA\Property(
     * format="bool", 
     * description="Расторгнутые, 1 - есть, 0 нет.", 
     * property="isclose", 
     * type="bool", 
     * example="1")
     * */
    public const ISCLOSE        = 'isclose';
    
     /**  @OA\Property(
     *      format="array", 
     *      description="Массив содержащий интервал даты ДКП от - до, параметр ДО необязателен, 
     *      отсутствие второго параметра, будет означать, что используется не интервал, 
     *      соответственно сравнение будет строго по одному параметру", 
     *      property="dkp", 
     *      type="array", 
     *      example="[01.10.2024,22.10.2024]", 
     *      @OA\Items()
     * )
     * */
    public const DKP            = 'dkp';

     /**  @OA\Property(
     *      format="array", 
     *      description="Массив содержащий интервал даты ПДКП от - до, параметр ДО необязателен, 
     *      отсутствие второго параметра, будет означать, что используется не интервал, 
     *      соответственно сравнение будет строго по одному параметру", 
     *      property="pdkp", 
     *      type="array", 
     *      example="[01.10.2024,22.10.2024]", 
     *      @OA\Items()
     * )
     * */
    public const PDKP           = 'pdkp';

     /**  @OA\Property(
     *      format="array", 
     *      description="Массив содержащий интервал даты Первичный контракт от - до, параметр ДО необязателен, 
     *      отсутствие второго параметра, будет означать, что используется не интервал, 
     *      соответственно сравнение будет строго по одному параметру", 
     *      property="create", 
     *      type="array", 
     *      example="[01.10.2024,22.10.2024]", 
     *      @OA\Items()
     * )
     * */
    public const CREATE         = 'create';

     /**  @OA\Property(
     *      format="array", 
     *      description="Массив содержащий интервал даты Расторжение от - до, параметр ДО необязателен, 
     *      отсутствие второго параметра, будет означать, что используется не интервал, 
     *      соответственно сравнение будет строго по одному параметру", 
     *      property="close", 
     *      type="array", 
     *      example="[01.10.2024,22.10.2024]", 
     *      @OA\Items()
     * )
     * */
    public const CLOSE          = 'close';

     /**  @OA\Property(
     *      format="array", 
     *      description="Массив содержащий интервал даты Продажа от - до, параметр ДО необязателен, 
     *      отсутствие второго параметра, будет означать, что используется не интервал, 
     *      соответственно сравнение будет строго по одному параметру", 
     *      property="sale", 
     *      type="array", 
     *      example="[01.10.2024,22.10.2024]", 
     *      @OA\Items()
     * )
     * */
    public const SALE           = 'sale';

    /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы Оформителей ДКП", 
     * property="dkp_manager", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const DKP_MANAGER    = 'dkp_manager';

    /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы Оформителей ПДКП", 
     * property="pdkp_manager", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const PDKP_MANAGER   = 'pdkp_manager';

    /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы Оформителей продажи", 
     * property="sale_manager", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const SALE_MANAGER   = 'sale_manager';

    /**  @OA\Property(
     * format="string", 
     * description="Полнотекстовый поиск, если перед строкой поиска подставить id@ будет искать по id машины. Возможны следующие уточнения:
     * id@ - id машины (точный поиск), vin@ - вин машины, order@ - номер заказа, client@ - фамилия клиента", 
     * property="search", 
     * type="string", 
     * example="1122")
     * */
    public const SEARCH = 'search';//Полнотекстовы поиск

    /**  @OA\Property(
     * format="integer", 
     * description="Вывод РЛ по выбранному статусу (список статусов work, confirm, check)", 
     * property="worksheet_status", 
     * type="integer", 
     * example="1")
     * */
    public const WORKSHEET_STATUS        = 'worksheet_status';
    
    /**  @OA\Property(
     * format="integer", 
     * description="Дебиторская задолженность (если есть рассторжение, то 0, иначе
     * (Контракт + Опции + Переоценка + Тюнинг – Подарки - Скидки) - (Оплата клиента + Трейд-ин))
     * 1 - есть, 0 - нет",
     * property="has_debit", 
     * type="integer", 
     * example="0")
     * */
    public const HAS_DEBIT = 'has_debit';

    /**  @OA\Property(
     * format="boolean", 
     * description="Наличие дкп 1 - да, 0 - нет", 
     * property="has_dkp", 
     * type="boolean", 
     * example="1")
     * */
    public const HAS_DKP = 'has_dkp';

    /**  @OA\Property(
     * format="boolean", 
     * description="Наличие пдкп 1 - да, 0 - нет", 
     * property="has_pdkp", 
     * type="boolean", 
     * example="1")
     * */
    public const HAS_PDKP = 'has_pdkp';

    /**  @OA\Property(
     * format="boolean", 
     * description="Наличие продажи 1 - да, 0 - нет", 
     * property="has_sale", 
     * type="boolean", 
     * example="1")
     * */
    public const HAS_SALE = 'has_sale';

    /**  @OA\Property(
     * format="integer", 
     * description="Наличие кредиторской задолженности (если есть продажа, то 0 иначе, оплата + стоимость трейдына)
     * 1 - есть задолженность, 0 - нет задолженности",
     * property="has_credit", 
     * type="integer", 
     * example="1")
     * */
    public const HAS_CREDIT = 'has_credit';



    protected function getCallbacks(): array
    {
        return [
            self::INIT              => [$this, 'init'],
            self::IDS               => [$this, 'ids'],
            self::OVERDUE           => [$this, 'overdue'],
            self::ISCLOSE           => [$this, 'isClose'],
            self::DKP               => [$this, 'dkp'],
            self::PDKP              => [$this, 'pdkp'],
            self::CREATE            => [$this, 'create'],
            self::CLOSE             => [$this, 'close'],
            self::SALE              => [$this, 'sale'],
            self::DKP_MANAGER       => [$this, 'dkp_manager'],
            self::PDKP_MANAGER      => [$this, 'pdkp_manager'],
            self::SALE_MANAGER      => [$this, 'sale_manager'],
            self::SEARCH            => [$this, 'search'],
            self::WORKSHEET_STATUS    => [$this, 'isWorksheetAbort'],
            self::HAS_DEBIT             => [$this, 'debitRelativeZero'],
            self::HAS_DKP               => [$this, 'hasDKP'],
            self::HAS_PDKP              => [$this, 'hasPDKP'],
            self::HAS_SALE              => [$this, 'hasSale'],
            self::HAS_CREDIT            => [$this, 'hasCredit'],
        ];
    }



    public function __construct(array $queryParams)
    {
        $queryParams['init'] = 'init';
        parent::__construct($queryParams);
    }



    public function init(Builder $builder)
    {
        $builder->leftJoin('wsm_reserve_new_cars',     'wsm_reserve_new_cars.id',    'wsm_reserve_new_car_contracts.reserve_id'); //Резерв
        $builder->leftJoin('cars',         'cars.id',        'wsm_reserve_new_cars.car_id'); //машина
        $builder->leftJoin('worksheets',   'worksheets.id',  'wsm_reserve_new_cars.worksheet_id'); //РЛ
        $builder->leftJoin('clients',      'clients.id',     'worksheets.client_id'); //Клиент
        $builder->leftJoin('wsm_reserve_sales', 'wsm_reserve_sales.reserve_id', 'wsm_reserve_new_cars.id');
        $builder->leftJoin('car_orders', 'car_orders.car_id', 'cars.id');

        //Complectation price
        $builder->leftJoin('wsm_reserve_complectation_prices as contract_cp', 'contract_cp.contract_id', 'wsm_reserve_new_car_contracts.id');
        $builder->leftJoin('complectation_prices as complect_price', 'complect_price.id', 'contract_cp.complectation_price_id');

        // Option price////////////////////////
        $builder->leftJoin(
            DB::raw('(SELECT sum(opt_price.price) as price, contract_op.contract_id FROM wsm_reserve_option_prices as contract_op
                LEFT JOIN option_prices as opt_price on opt_price.id = contract_op.option_price_id
                GROUP BY contract_op.contract_id)
                as _options
            '),
            '_options.contract_id',
            'wsm_reserve_new_car_contracts.id'
        );

        // Overprice Tuning Gift
        $builder->leftJoin('car_full_prices as car_fp', 'car_fp.car_id', 'cars.id');

        // discounts
        $builder->leftJoin(
            DB::raw('(SELECT discounts.modulable_id as reserve_id, sum(discount_sums.amount) as amount FROM discounts
                LEFT JOIN discount_sums on discount_sums.discount_id = discounts.id
                WHERE discounts.modulable_type = "App\\\Models\\\WsmReserveNewCar"
                GROUP BY discounts.modulable_id) as _discounts'
            ),
            '_discounts.reserve_id',
            'wsm_reserve_new_car_contracts.reserve_id'
        );

        // trade-In
        $builder->leftJoin(
            DB::raw('(SELECT wrt.reserve_id as reserve_id, sum(used_cars.purchase_price) as price 
                FROM wsm_reserve_trade_ins as wrt
                LEFT JOIN used_cars on used_cars.id = wrt.used_car_id
                GROUP BY wrt.reserve_id) as _tradins'),
            '_tradins.reserve_id',
            'wsm_reserve_new_car_contracts.reserve_id'
        );

        //payments
        $builder->leftJoin(
            DB::raw('(SELECT wrp.reserve_id as reserve_id, sum(wrp.amount) as amount 
                FROM wsm_reserve_payments as wrp 
                GROUP BY wrp.reserve_id) as _payments'
            ),
            '_payments.reserve_id',
            'wsm_reserve_new_car_contracts.reserve_id'
        );

        $builder->groupBy('wsm_reserve_new_car_contracts.id');

        $builder->addSelect([
            DB::raw('IF(complect_price.price IS NULL, car_fp.complectationprice, complect_price.price) as cpprice'),
            DB::raw('IF(_options.price IS NULL, car_fp.optionprice, _options.price) as optprice'),
            'car_fp.overprice as cfpover',
            'car_fp.tuningprice as cfptuning',
            'car_fp.giftprice as cfpgift',            
            '_discounts.amount as dsamount',
            '_payments.amount as payamount',
            '_tradins.price as usedprice',
        ]);

        $builder->whereNotNull('cars.id');

        $builder->whereNotNull('wsm_reserve_new_cars.id');
    }



    /**
     * Наличие кредиторской задолженности
     */
    public function hasCredit(Builder $builder, bool $val)
    {
        $znak = match($val) {
            true => '>',
            false => '<=',
            default => null
        }; 

        if(!$znak)
            return;

        $builder->where(DB::raw('
            IF(
                wsm_reserve_sales.id is not null,
                0,
                IFNULL(_payments.amount, 0) + IFNULL(_tradins.price, 0)
            )
        '), $znak, 0);
    }



    /**
     * Наличие ДКП
     */
    public function hasDKP(Builder $builder, bool $val)
    {
        if($val)
            $builder->whereNotNull('wsm_reserve_new_car_contracts.dkp_offer_at');
        else
            $builder->whereNull('wsm_reserve_new_car_contracts.dkp_offer_at');
    }



    /**
     * Наличие ПДКП
     */
    public function hasPDKP(Builder $builder, bool $val)
    {
        if($val)
            $builder->whereNotNull('wsm_reserve_new_car_contracts.pdkp_offer_at');
        else
            $builder->whereNull('wsm_reserve_new_car_contracts.pdkp_offer_at');
    }



    /**
     * Наличие продажи
     */
    public function hasSale(Builder $builder, bool $val)
    {   
        if($val)
            $builder->whereNotNull('wsm_reserve_sales.id');
        else
            $builder->whereNull('wsm_reserve_sales.id');
    }



    /**
     * Наличие дебиторской задолженности
     */
    public function debitRelativeZero(Builder $builder, int $val)
    {
        $znak = match($val){
            -1 => '<',
            0 => '<=',
            1 => '>',
            default => null,
        };

        if(!$znak)
            return;

        $builder->where(DB::raw('IF(
            wsm_reserve_new_car_contracts.dkp_closed_at is not null, 
            0, (
                IF(complect_price.price IS NULL, car_fp.complectationprice, complect_price.price) + 
                IF(_options.price IS NULL, car_fp.optionprice, _options.price) + 
                IFNULL(car_fp.overprice,0) + 
                IFNULL(car_fp.tuningprice, 0) - 
                IFNULL(car_fp.giftprice, 0) - 
                IFNULL(_discounts.amount, 0) - 
                IFNULL(_payments.amount, 0) - 
                IFNULL(_tradins.price, 0)
            )
        )'), $znak, 0);
    }



    /**
     * Статус рабочего листв
     */
    public function isWorksheetAbort(Builder $builder, string $val)
    {
        if($val === 'confirm')
            $builder->where('worksheets.status_id', 'confirm');
        elseif($val === 'work')
            $builder->where('worksheets.status_id', 'work');
        elseif($val === 'check')
            $builder->where('worksheets.status_id', 'check');
    }



    /**
     * Выбранные ИД
     */
    public function ids(Builder $builder, array $array)
    {
        $builder->whereIn('wsm_reserve_new_car_contracts.id', $array);
    }



    /**
     * Наличие просроченной поставки
     */
    public function overdue(Builder $builder, bool $val)
    {
        if($val)
            $builder->where(function($q){
                $q->whereNotNull('wsm_reserve_new_car_contracts.pdkp_delivery_at');
                $q->whereDate('wsm_reserve_new_car_contracts.pdkp_delivery_at', '<', now());
                $q->whereNull('wsm_reserve_new_car_contracts.dkp_offer_at');
                $q->whereNull('wsm_reserve_new_car_contracts.dkp_closed_at');
            });
        else
            $builder->where(function($q){
                $q->whereNotNull('wsm_reserve_new_car_contracts.pdkp_delivery_at');
                $q->whereDate('wsm_reserve_new_car_contracts.pdkp_delivery_at', '>=', now());
                $q->whereNull('wsm_reserve_new_car_contracts.dkp_offer_at');
                $q->whereNull('wsm_reserve_new_car_contracts.dkp_closed_at');
            });
    }



    /**
     * Наличие расторжения
     */
    public function isClose(Builder $builder, bool $val)
    {
        if($val)
            $builder->whereNotNull('wsm_reserve_new_car_contracts.dkp_closed_at');
        else
            $builder->whereNull('wsm_reserve_new_car_contracts.dkp_closed_at');
    }



    /**
     * Дата создания (первичный контракт)
     */
    public function create(Builder $builder, array $dates)
    {
        $date_1 = Carbon::createFromFormat('d.m.Y', $dates[0]);
        $date_2 = isset($dates[1]) ? Carbon::createFromFormat('d.m.Y', $dates[1]) : $date_1;
        $date_1->setHour(0)->setMinute(0)->setSecond(0);
        $date_2->setHour(23)->setMinute(59)->setSecond(59);
        $builder->whereBetween('wsm_reserve_new_car_contracts.created_at', [$date_1, $date_2]);
    }



    /**
     * Дата заключения ДКП
     */
    public function dkp(Builder $builder, array $dates)
    {  
        $date_1 = Carbon::createFromFormat('d.m.Y', $dates[0]);
        $date_2 = isset($dates[1]) ? Carbon::createFromFormat('d.m.Y', $dates[1]) : $date_1;
        $date_1->setHour(0)->setMinute(0)->setSecond(0);
        $date_2->setHour(23)->setMinute(59)->setSecond(59);
        $builder->whereBetween('wsm_reserve_new_car_contracts.dkp_offer_at', [$date_1, $date_2]);
    }



    /**
     * Дата заключения ПДКП
     */
    public function pdkp(Builder $builder, array $dates)
    {
        $date_1 = Carbon::createFromFormat('d.m.Y', $dates[0]);
        $date_2 = isset($dates[1]) ? Carbon::createFromFormat('d.m.Y', $dates[1]) : $date_1;
        $date_1->setHour(0)->setMinute(0)->setSecond(0);
        $date_2->setHour(23)->setMinute(59)->setSecond(59);
        $builder->whereBetween('wsm_reserve_new_car_contracts.pdkp_offer_at', [$date_1, $date_2]);
    }



    /**
     * Дата продажи
     */
    public function sale(Builder $builder, array $dates)
    {
        $date_1 = Carbon::createFromFormat('d.m.Y', $dates[0]);
        $date_2 = isset($dates[1]) ? Carbon::createFromFormat('d.m.Y', $dates[1]) : $date_1;
        $date_1->setHour(0)->setMinute(0)->setSecond(0);
        $date_2->setHour(23)->setMinute(59)->setSecond(59);
        $builder->whereBetween('wsm_reserve_sales.date_at', [$date_1, $date_2]);
    }



    /**
     * Дата расторжения
     */
    public function close(Builder $builder, array $dates)
    {
        $date_1 = Carbon::createFromFormat('d.m.Y', $dates[0]);
        $date_2 = isset($dates[1]) ? Carbon::createFromFormat('d.m.Y', $dates[1]) : $date_1;
        $date_1->setHour(0)->setMinute(0)->setSecond(0);
        $date_2->setHour(23)->setMinute(59)->setSecond(59);
        $builder->whereBetween('wsm_reserve_new_car_contracts.dkp_closed_at', [$date_1, $date_2]);
    }



    /**
     * Менеджер ДКП
     */
    public function dkp_manager(Builder $builder, array $val)
    {
        $builder->whereIn('wsm_reserve_new_car_contracts.dkp_decorator_id', $val);
    }



    /**
     * Менеджер ПДКП
     */
    public function pdkp_manager(Builder $builder, array $val)
    {
        $builder->whereIn('wsm_reserve_new_car_contracts.pdkp_decorator_id', $val);
    }



    /**
     * Оформитель продажи
     */
    public function sale_manager(Builder $builder, array $val)
    {
        $builder->whereIn('wsm_reserve_sales.decorator_id', $val);
    }



    public function search(Builder $builder, string $value)
    {
        $value = str_contains($value, '@') ? $value : '@'.$value;
        $value = explode('@', $value);
        list($column, $val) = $value;

        switch($column){
            case 'id':
                $builder->where('cars.id', $val);
                break;
            case 'vin':
                $builder->where('cars.vin', 'LIKE', '%' . $val . '%');
                break;
            case 'order':
                $builder->where('car_orders.order_number', 'LIKE', '%' . $val . '%');
                break;
            case 'client':
                $builder->where(function($subQ) use ($val){
                    $subQ->where('clients.lastname', 'LIKE', '%'.$val.'%');
                    $subQ->orWhere('clients.company_name', 'LIKE', '%'.$val.'%');
                });
                break;
            default:
                $builder->where(function ($query) use ($val) {
                    $query->where('cars.vin',                   'LIKE', '%' . $val . '%')
                        ->orWhere('cars.id',                    'LIKE', '%' . $val . '%')
                        ->orWhere('car_orders.order_number',    'LIKE', '%' . $val . '%');
                });
                break;
        }
    }
}
