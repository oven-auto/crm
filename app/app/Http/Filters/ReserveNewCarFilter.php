<?php

namespace App\Http\Filters;

use App\Helpers\Date\DateHelper;
use App\Models\CarState;
use App\Models\CarStatusType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Schema(
 *   description = "Параметры фильтрации резерва"
 * )
 */
class ReserveNewCarFilter extends AbstractFilter
{
    /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы автомобилей", 
     * property="ids", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const IDS                        = 'ids';
    public const TRASH                      = 'trash';
    public const VIN                        = 'vin';
    public const INIT                       = 'init';
    public const COMPLECTATION_CODE         = 'complectation_code';

    /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы участников РЛ", 
     * property="executors", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const EXECUTORS                  = 'executors';

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
    public const BRAND                      = 'brands';

    /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы моделей", 
     * property="models", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const MARK                       = 'models';

    /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий интервал цены от - до, параметр ДО необязателен, 
     * отсутствие второго параметра, будет означать, что используется не интервал, 
     * соответственно сравнение будет строго по одному параметру", 
     * property="prices", 
     * type="array", 
     * example="[700000,1000000]", 
     * @OA\Items())
     * */
    public const PRICES                     = 'prices';

    /**  @OA\Property(
     * format="integer", 
     * description="Год выпуска", 
     * property="year", 
     * type="integer", 
     * example="2024")
     * */
    public const YEAR                       = 'year';

    /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы типов кузова", 
     * property="bodyworks", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const BODY                       = 'bodyworks';

    /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы типов двигателя", 
     * property="motortypes", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const MOTOR_TYPE                 = 'motortypes';

    /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы типов трансмиссий", 
     * property="transmissions", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const TRANSMISSION               = 'transmissions';

    /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы типов привода", 
     * property="drivers", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const DRIVER                     = 'drivers';

    /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы цвета (БАЗОВЫЕ ЦВЕТА - НЕ ДИЛЕРСКИЕ)", 
     * property="colors", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const COLOR                      = 'colors';

     /**  @OA\Property(
     * format="bool", 
     * description="Наличие опций", 
     * property="has_options", 
     * type="bool"
     * )
     * */
    public const HAS_OPTIONS = 'has_options';

    /**  @OA\Property(
     *      format="bool", 
     *      description="Наличие переоценки, 1 - есть, 0 нет.", 
     *      property="has_overprice", 
     *      type="bool", 
     *      example="1"
     * )
     * */
    public const HAS_OVERPRICE = 'has_overprice';

    /**  @OA\Property(
     *      format="bool", 
     *      description="Наличие тюнинга, 1 - есть, 0 нет.", 
     *      property="has_tuning", 
     *      type="bool", 
     *      example="1")
     * */
    public const HAS_TUNING = 'has_tuning';

     /**  @OA\Property(
     * format="bool", 
     * description="Наличие установленого тюнинга(номенклатурно)", 
     * property="has_devices", 
     * type="bool"
     * )
     * */
    public const HAS_DEVICES = 'has_devices';

    /**  @OA\Property(
     * format="bool", 
     * description="Наличие подарка, 1 - есть, 0 нет.", 
     * property="has_gift", 
     * type="bool", 
     * example="1")
     * */
    public const HAS_GIFT = 'has_gift';

    /**  @OA\Property(
     * format="bool", 
     * description="Наличие скидки, 1 - есть, 0 нет.", 
     * property="has_discount", 
     * type="bool", 
     * example="1")
     * */
    public const HAS_DISCOUNT = 'has_discount';

     /**  @OA\Property(
     * format="bool", 
     * description="Наличие выкупного платежа", 
     * property="has_ransom", 
     * type="bool"
     * )
     * */
    public const HAS_RANSOM = 'has_ransom';

    /**  @OA\Property(
     * format="bool", 
     * description="Наличие дополнительной себестоимости, 1 - есть, 0 нет.", 
     * property="has_detailing_cost", 
     * type="bool", 
     * example="1")
     * */
    public const HAS_DETAILING_COST = 'has_detailing_cost';   




    /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы текущих этапов поставки", 
     * property="logistic_statuses", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const STATE_STATUS = 'logistic_statuses';//Этап поставки 

    /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы типов товарных признаков ", 
     * property="trade_markers", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const TRADE_MARKER_ID = 'trade_markers';//Товарный признак

    /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы контрмарки", 
     * property="markers", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const MARKER_ID = 'markers';//Контрмарка

     /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий интервал даты этапа создание резерва от - до, параметр ДО необязателен, 
     * отсутствие второго параметра, будет означать, что используется не интервал, 
     * соответственно сравнение будет строго по одному параметру", 
     * property="reserve_date", 
     * type="array", 
     * example="[01.10.2024,22.10.2024]", 
     * @OA\Items())
     * */
    public const RESERVE_DATE = 'reserve_date';//интервал резерва

    /**
     * @OA\Property(
     *  format="bool", 
     *  description="Наличие контракта", 
     *  property="has_contract", 
     *  type="bool"
     * )
     */
    public const HAS_CONTRACT = 'has_contract';//есть контракт

     /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий интервал даты этапа первичноый контракт от - до, параметр ДО необязателен, 
     * отсутствие второго параметра, будет означать, что используется не интервал, 
     * соответственно сравнение будет строго по одному параметру", 
     * property="contract_date", 
     * type="array", 
     * example="[01.10.2024,22.10.2024]", 
     * @OA\Items())
     * */
    public const CONTRACT_DATE = 'contract_date';//Первичный контракт

     /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий интервал даты этапа ДКП от - до, параметр ДО необязателен, 
     * отсутствие второго параметра, будет означать, что используется не интервал, 
     * соответственно сравнение будет строго по одному параметру", 
     * property="dkp_date", 
     * type="array", 
     * example="[01.10.2024,22.10.2024]", 
     * @OA\Items())
     * */
    public const DKP_DATE = 'dkp_date';//dkp date

     /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий интервал даты этапа ПДКП от - до, параметр ДО необязателен, 
     * отсутствие второго параметра, будет означать, что используется не интервал, 
     * соответственно сравнение будет строго по одному параметру", 
     * property="pdkp_date", 
     * type="array", 
     * example="[01.10.2024,22.10.2024]", 
     * @OA\Items())
     * */
    public const PDKP_DATE = 'pdkp_date';//pdkp date

    /**
     * @OA\Property(
     *  format="bool", 
     *  description="Наличие оплаты", 
     *  property="has_client_pay", 
     *  type="bool"
     * )
     */
    public const HAS_CLIENT_PAY = 'has_client_pay';

    /**
     * @OA\Property(
     *  format="bool", 
     *  description="Наличие выдачи", 
     *  property="has_issue", 
     *  type="bool"
     * )
     */
    public const HAS_ISSUE = 'has_issue';

    /**
     * @OA\Property(
     *  format="bool", 
     *  description="Наличие продажи", 
     *  property="has_sale", 
     *  type="bool"
     * )
     */
    public const HAS_SALE = 'has_sale';

     /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий интервал даты этапа продажи от - до, параметр ДО необязателен, 
     * отсутствие второго параметра, будет означать, что используется не интервал, 
     * соответственно сравнение будет строго по одному параметру", 
     * property="sale_date", 
     * type="array", 
     * example="[01.10.2024,22.10.2024]", 
     * @OA\Items())
     * */
    public const SALE_DATE = 'sale_date';

    /**  @OA\Property(
     * format="bool", 
     * description="Наличие списания, 1 - есть, 0 нет.", 
     * property="has_off", 
     * type="bool", 
     * example="1")
     * */
    public const HAS_OFF = 'has_off';

     /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий тип рапотра", 
     * property="report_type", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const REPORT_TYPE = 'report_type';

     /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий интервал даты этапа списание от - до, параметр ДО необязателен, 
     * отсутствие второго параметра, будет означать, что используется не интервал, 
     * соответственно сравнение будет строго по одному параметру", 
     * property="off_date", 
     * type="array", 
     * example="[01.10.2024,22.10.2024]", 
     * @OA\Items())
     * */
    public const OFF_DATE = 'off_date';//Все логистические даты(используется мидлвар)

    /**  @OA\Property(
     * format="bool", 
     * description="Наличие в плане поставщика, 1 - есть, 0 нет.", 
     * property="has_plan", 
     * type="bool", 
     * example="1")
     * */
    public const HAS_PLAN = 'has_plan';

    /**  @OA\Property(
     * format="string", 
     * description="Полнотекстовый поиск, если перед строкой поиска подставить id@ будет искать по id машины. Возможны следующие уточнения:
     * id@ - id машины (точный поиск), vin@ - вин машины, order@ - номер заказа, model@ - модель машины", 
     * property="search", 
     * type="string", 
     * example="1122")
     * */
    public const SEARCH = 'search';//Полнотекстовы поиск

        /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы приоритетов", 
     * property="priority_ids", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const PRIORITY_IDS = 'priority_ids';

    /**
     * @OA\Property(
     *  format="bool", 
     *  description="Приортиет 1 - есть, 0 - нет", 
     *  property="has_priority", 
     *  type="bool"
     * )
     */
    public const HAS_PRIORITY = 'has_priority';

    /**  
     * @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы авторов РЛ", 
     * property="ws_authors", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const WS_AUTHORS = 'ws_authors';

    /**  
     * @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы авторов резерва", 
     * property="reserve_authors", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const RESERVE_AUTHORS = 'reserve_authors';

    /**  
     * @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы оформителей ПДКП", 
     * property="pdkp_decorators", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const PDKP_DECORATORS = 'pdkp_decorators';

    /**  
     * @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы оформителей ДКП", 
     * property="dkp_decorators", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const DKP_DECORATORS = 'dkp_decorators';

    /**  
     * @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы оформителей выдачи", 
     * property="issue_managers", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const ISSUE_MANAGERS = 'issue_managers';

    /**  
     * @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы оформителей продажи", 
     * property="sale_managers", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const SALE_MANAGERS = 'sale_managers';

    /**  
     * @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы принимающий техник", 
     * property="technics", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const TECHNICS = 'technics';

    public const LOGISTIC_DATES = 'logistic_dates';

    public const SORT = 'sort';

    public const POWER = 'power';

    public const TYPE_STATUS = 'type_statuses';

    public const HAS_TRADEIN = 'has_tradein';

    public const HAS_DEBIT = 'has_debit';

    public const HAS_PAID_DATE = 'has_paid_date';

    protected function getCallbacks(): array
    {
        return [
            self::HAS_PAID_DATE                     => [$this, 'hasPaidDate'],
            self::PRIORITY_IDS                      => [$this, 'priorityIds'],
            self::HAS_PRIORITY                      => [$this, 'hasPriority'],
            self::SEARCH                            => [$this, 'search'],
            self::INIT                              => [$this, 'init'],
            self::IDS                               => [$this, 'ids'],
            self::TRASH                             => [$this, 'trash'],
            self::VIN                               => [$this, 'vin'],
            self::BRAND                             => [$this, 'brand'],
            self::MARK                              => [$this, 'mark'],
            self::COMPLECTATION_CODE                => [$this, 'complectationCode'],          
            self::PRICES                            => [$this, 'price'],
            self::YEAR                              => [$this, 'year'],
            self::MOTOR_TYPE                        => [$this, 'motorTypes'],
            self::BODY                              => [$this, 'bodyWork'],
            self::TRANSMISSION                      => [$this, 'transmission'],
            self::DRIVER                            => [$this, 'drivers'],
            self::COLOR                             => [$this, 'colors'],
            self::HAS_OPTIONS                       => [$this, 'hasOptions'],
            self::HAS_OVERPRICE                     => [$this, 'hasOverprice'],
            self::HAS_TUNING                        => [$this, 'hasTuning'],
            self::HAS_DEVICES                       => [$this, 'hasDevices'],
            self::HAS_GIFT                          => [$this, 'hasGift'],
            self::HAS_DISCOUNT                      => [$this, 'hasDiscount'],
            self::HAS_RANSOM                        => [$this, 'hasRansom'],
            self::HAS_DETAILING_COST                => [$this, 'hasDetailingCost'],           
            self::STATE_STATUS                      => [$this, 'stateStatus'],
            self::TRADE_MARKER_ID                   => [$this, 'tradeMarkerId'],
            self::MARKER_ID                         => [$this, 'markerId'],
            self::RESERVE_DATE                      => [$this, 'reserveDate'],
            self::HAS_CONTRACT                      => [$this, 'hasContract'],
            self::CONTRACT_DATE                     => [$this, 'contractDate'],
            self::DKP_DATE                          => [$this, 'dkpDate'],
            self::PDKP_DATE                         => [$this, 'pdkpDate'],
            self::HAS_CLIENT_PAY                    => [$this, 'hasClientPay'],
            self::HAS_ISSUE                         => [$this, 'hasIssue'],
            self::HAS_SALE                          => [$this, 'hasSale'],
            self::SALE_DATE                         => [$this, 'saleDate'],
            self::HAS_OFF                           => [$this, 'hasOff'],
            self::REPORT_TYPE                       => [$this, 'reportType'],
            self::HAS_PLAN                          => [$this, 'hasPlan'],
            self::LOGISTIC_DATES                    => [$this, 'logisticDates'],
            self::POWER                             => [$this, 'power'],
            self::HAS_TRADEIN                       => [$this, 'has_tradein'],
            self::SORT                              => [$this, 'sort'],
            self::TYPE_STATUS                       => [$this, 'typeStatuses'],
            self::WS_AUTHORS                        => [$this, 'wsAuthors'],
            self::RESERVE_AUTHORS                   => [$this, 'reserveAuthors'],
            self::DKP_DECORATORS                    => [$this, 'dkpDecorators'],
            self::PDKP_DECORATORS                   => [$this, 'pdkpDecorators'],
            self::ISSUE_MANAGERS                    => [$this, 'issueManagers'],
            self::SALE_MANAGERS                     => [$this, 'saleManager'],
            self::EXECUTORS                         => [$this, 'executors'],
            self::TECHNICS                          => [$this, 'technics'],
            self::HAS_DEBIT                         => [$this, 'hasDebit'],
        ];
    }



    public function hasDebit(Builder $builder, bool $val)
    {
        $query = '  IFNULL(cfp.tuningprice, 0) + 
                    
                    IFNULL(cfp.overprice, 0) + 
                    
                    IF(cp.price IS NOT NULL, cp.price, cfp.complectationprice) + 
                    
                    IF(joinOptionPrice.sum_option IS NOT NULL, joinOptionPrice.sum_option, cfp.optionprice) - 
                    
                    IFNULL(cfp.giftprice, 0) - 

                    IFNULL(_joinds._dsum, 0) - 

                    IFNULL((SELECT SUM(used_cars.purchase_price) FROM used_cars WHERE used_cars.id = wsm_reserve_trade_ins.used_car_id), 0) - 
                    
                    IFNULL((SELECT SUM(amount) FROM wsm_reserve_payments WHERE wsm_reserve_payments.reserve_id = wsm_reserve_new_cars.id), 0)';

        match($val){
            true => $builder->WHERERaw($query.' > 0'),
            false =>$builder->WHERERaw($query.' <= 0'),
            default => '',
        };

        $builder->addSelect([
            DB::raw('('.$query.') as sss'),
            DB::raw('joinOptionPrice.sum_option as ppp'),
            DB::raw('cfp.optionprice as ooo')
        ]);
    }

    

    public function executors(Builder $builder, array $array)
    {
        $builder->WHEREIn('worksheet_executors.user_id', $array);
    }



    public function wsAuthors(Builder $builder, array $arr)
    {
        $builder->WHEREIn('worksheets.author_id', $arr);
    }



    public function reserveAuthors(Builder $builder, array $arr)
    {
        $builder->WHEREIn('wsm_reserve_new_cars.author_id', $arr);
    }



    public function dkpDecorators(Builder $builder, array $arr)
    {
        $builder->WHEREIn('contract.dkp_decorator_id', $arr);
    }



    public function pdkpDecorators(Builder $builder, array $arr)
    {
        $builder->WHEREIn('contract.pdkp_decorator_id', $arr);
    }



    public function issueManagers(Builder $builder, array $arr)
    {
        $builder->WHEREIn('wsm_reserve_issues.decorator_id', $arr);
    }



    public function saleManager(Builder $builder, array $arr)
    {
        $builder->WHEREIn('wsm_reserve_sales.decorator_id', $arr);
    }



    public function technics(Builder $builder, array $arr)
    {
        $builder->WHEREIn('car_technics.technic_id', $arr);
    }



    public function __construct(array $queryParams)
    {   
        $queryParams['init'] = $queryParams;

        parent::__construct($queryParams);
    }



    public function init(Builder $builder, array $params)
    {
        $builder
            ->leftJoin('cars', 'cars.id', 'wsm_reserve_new_cars.car_id') //машина
            ->leftJoin('complectations', 'complectations.id', 'cars.complectation_id') //комплектация машины
            ->leftJoin('motors', 'motors.id', 'complectations.motor_id')
            ->leftJoin('worksheets', 'worksheets.id', 'wsm_reserve_new_cars.worksheet_id') //РЛ
            ->leftJoin('worksheet_executors', 'worksheet_executors.worksheet_id', 'worksheets.id') //Участники РЛ
            ->leftJoin('clients', 'clients.id', 'worksheets.client_id') //Клиент
            ->leftJoin('wsm_reserve_new_car_contracts as contract', 'contract.reserve_id', 'wsm_reserve_new_cars.id')
            ->leftJoin('car_sale_priorities', 'car_sale_priorities.car_id', 'cars.id'); 
        
        $this->setJoinToSearch($builder, $params);

        if(isset($queryParams['having']) && $queryParams['having'] > 0)
            $builder->havingRaw('count(cars.id) > '.($queryParams['having']-1));
    }



    public function setJoinToSearch(Builder $builder, array $params)
    {
        $builder ->leftJoin('car_full_prices as cfp', 'cfp.car_id', 'cars.id')//представление хранящее актуальную цену авто по прайсу
            ->leftJoin('wsm_reserve_complectation_prices as wrcp','wrcp.contract_id', 'contract.id')//сохраненая в контракте цена
            ->leftJoin('complectation_prices as cp', 'cp.id', 'wrcp.complectation_price_id')//цены комплектации
            ->leftJoin('wsm_reserve_option_prices as wrop', 'wrop.contract_id', 'contract.id')//сохраненные в контракте опции
            
            ->leftJoin(DB::raw('(
                SELECT 
                    sum(option_prices.price) as sum_option, 
                    wsm_reserve_new_cars.car_id 
                FROM option_prices 
                LEFT JOIN 
                    wsm_reserve_option_prices on wsm_reserve_option_prices.option_price_id = option_prices.id 
                LEFT JOIN 
                    wsm_reserve_new_car_contracts on wsm_reserve_new_car_contracts.id = wsm_reserve_option_prices.contract_id 
                LEFT JOIN 
                    wsm_reserve_new_cars on wsm_reserve_new_cars.id = wsm_reserve_new_car_contracts.reserve_id 
                WHERE 
                    wsm_reserve_new_cars.car_id is not null and 
                    wsm_reserve_new_cars.deleted_at is null
                GROUP  BY  wsm_reserve_new_cars.car_id
                ) as joinOptionPrice'), 'joinOptionPrice.car_id', 'cars.id'
            )

            ->addSelect([
                'joinOptionPrice.sum_option as _sum_option',
                'cp.price                   as _cp_price', 
                'cp.id                      as _cp_id', 
            ]);
    
        $builder->leftJoin('car_gift_prices as gift', 'gift.car_id', 'cars.id')//gift
            ->addSelect([
                'gift.price as _gift_price', 
            ]);
    
        $builder->leftJoin('car_over_prices as overprice', 'overprice.car_id', 'cars.id')//overprice
            ->addSelect([
                'overprice.price as _over_price',
            ]);

        $builder->leftJoin('car_tuning_prices as tuning', 'tuning.car_id', 'cars.id')//цена тюнинга
            ->addSelect([
                'tuning.price as _tuning_price',
            ]);

        $builder->leftJoin('car_options', 'car_options.car_id', 'cars.id');

        $builder->leftJoin('car_tunings', 'car_tunings.car_id', 'cars.id');

        $builder->leftJoin('discounts', function($join){
            $join->on('discounts.worksheet_id', '=', 'worksheets.id')
                ->on('discounts.modulable_type', '=', DB::raw('"App\\\Models\\\WsmReserveNewCar"'))
                ->on('discounts.modulable_id', 'wsm_reserve_new_cars.id');
        });

        $builder->leftJoin('ransom_cars', 'ransom_cars.car_id', 'cars.id'); 

        $builder->leftJoin('car_detailing_costs', 'car_detailing_costs.car_id', 'cars.id');

        $builder->leftJoin('car_trade_markers', 'car_trade_markers.car_id', 'cars.id');

        $builder->leftJoin('car_markers', 'car_markers.car_id', 'cars.id');

        $builder->leftJoin('wsm_reserve_payments', 'wsm_reserve_payments.reserve_id', 'wsm_reserve_new_cars.id');

        $builder->leftJoin('wsm_reserve_issues', 'wsm_reserve_issues.reserve_id', 'wsm_reserve_new_cars.id');

        $builder->leftJoin('wsm_reserve_sales', 'wsm_reserve_sales.reserve_id', 'wsm_reserve_new_cars.id');

        $builder->leftJoin('car_owners', 'car_owners.car_id', 'cars.id');
        
        $builder->leftJoin('car_date_logistics', 'car_date_logistics.car_id', 'cars.id');

        $builder->leftJoin('dealer_colors', 'dealer_colors.id', 'cars.color_id');

        $builder->leftJoin('marks', 'marks.id', 'cars.mark_id');

        $builder->leftJoin('brands', 'brands.id', 'cars.brand_id');

        $builder->leftJoin('car_status_types', 'car_status_types.car_id', 'cars.id');

        $builder->leftJoin('wsm_reserve_trade_ins', 'wsm_reserve_trade_ins.reserve_id', 'wsm_reserve_new_cars.id');

        //$builder->leftJoin('used_cars', 'used_cars.id', 'wsm_reserve_trade_ins.used_car_id');
        
        $builder->leftJoin('wsm_reserve_lisings', 'wsm_reserve_lisings.reserve_id', 'wsm_reserve_new_cars.id');

        $builder->leftJoin('car_technics', 'car_technics.car_id', 'cars.id');
        
        $builder->leftJoin(DB::raw('(
            SELECT sum(_ds.amount) as _dsum, _d.modulable_id as _dreserve FROM discounts as _d 
            LEFT JOIN discount_sums as _ds on _ds.discount_id = _d.id group by _d.modulable_id
            ) as _joinds'), '_joinds._dreserve', 'wsm_reserve_new_cars.id'
        );
        
        $builder->leftJoin('wsm_reserve_planned_payments', 'wsm_reserve_planned_payments.reserve_id', 'wsm_reserve_new_cars.id');

        $builder->groupBy('wsm_reserve_new_cars.id');
    }



    public function hasPaidDate(Builder $builder, bool $val)
    {
        match($val){
            true => $builder->WHERENotNull('wsm_reserve_planned_payments.id'),
            false => $builder->WHERENull('wsm_reserve_planned_payments.id'),
            default => ''
        };        
    }



    public function hasPriority(Builder $builder, bool $val)
    {
        match($val){
            true => $builder->WHERENotNull('car_sale_priorities.priority_id'),
            false => $builder->WHERENull('car_sale_priorities.priority_id'),
            default => null,
        };
    }



    public function priorityIds(Builder $builder, array $arr)
    {
        $builder->WHEREIn('car_sale_priorities.priority_id', $arr);
    }



    public function has_tradein(Builder $builder, $val)
    {
        if($val)
            $builder->WHERENotNull('wsm_reserve_trade_ins.reserve_id');
        else
            $builder->WHERENull('wsm_reserve_trade_ins.reserve_id');
    }   



    public function power(Builder $builder, array $power)
    {
        if(count($power) == 1)
            $power[] = $power[0];
        $builder->WHEREBetween('motors.power', $power);
    }



    public function typeStatuses(Builder $builder, array $value)
    {
        $arr = array_unique($value);

        $result = array_intersect($arr, CarStatusType::VALUES);

        $builder->WHEREIn('car_status_types.status', $result);
    }



    public function sort(Builder $builder, string $val)
    {
        match($val){
            'sale_old'      => $builder
                ->orderBy(DB::raw('IF(wsm_reserve_sales.id IS NOT NULL, 0, 1)'))
                ->orderBy('wsm_reserve_sales.date_at', 'ASC'),
            'sale_new'      => $builder
                ->orderBy(DB::raw('IF(wsm_reserve_sales.id IS NOT NULL, 0, 1)'))
                ->orderBy('wsm_reserve_sales.date_at', 'DESC'),            
            'name_asc' => $builder
                ->orderBy('brands.name', 'ASC')
                ->orderBy('marks.name', 'ASC'),
            'name_desc' => $builder
                ->orderBy('brands.name', 'DESC')
                ->orderBy('marks.name', 'DESC'),
            default => '',
        };
    }



    /**
     * Выделенные ИД
     */
    public function ids(Builder $builder, array $array)
    {
        $builder->WHEREIn('wsm_reserve_new_cars.id', $array);
    }



    /**
     * Фильтр цены
     */
    public function price(Builder $builder, array $arrPrices)
    {
        $price_1 = $arrPrices[0];
        $price_2 = $arrPrices[1] ??  $arrPrices[0];
        
        $str = 'IF(joinOptionPrice.sum_option IS NOT NULL, joinOptionPrice.sum_option, 0) + 
            if(cp.price IS NOT NULL, cp.price, 0) + 
            if(overprice.price is not null, overprice.price, 0) +
            if(tuning.price IS NOT NULL, tuning.price, 0) - 
            if(gift.price is NOT NULL, gift.price, 0)';

        $havingWithContract = '(('.$str.') >= '.$price_1.' and ('.$str.') <= '.$price_2.')';

        $havingWithOutContract = '((MAX(cfp.price) >= '.$price_1.' and max(cfp.price) <= '.$price_2.'))';

        $builder->havingRaw('IF(cp.id IS NOT NULL, '.$havingWithContract.', '.$havingWithOutContract.')');
    }



    /**
     * Бренд автомобиля
     */
    public function brand(Builder $builder, array $value)
    {
        $builder->WHEREIn('cars.brand_id', $value);
    }



    /**
     * Модель автомобиля
     */
    public function mark(Builder $builder, array $value)
    {
        $builder->WHEREIn('cars.mark_id', $value);
    }



    /**
     * CAR YEAR
     */
    public function year(Builder $builder, int $value)
    {
        $builder->WHERE('cars.year', $value);
    }



    /**
     * BODYWORK
     */
    public function bodyWork(Builder $builder, array $value)
    {
        $builder->WHEREIn('complectations.body_work_id', $value);
    }



     /**
     * Трансмиссия
     */
    public function transmission(Builder $builder, array $value)
    {
        $builder->WHEREIn('motors.motor_transmission_id', $value);
    }



    /**
     * Привод
     */
    public function drivers(Builder $builder, array $value)
    {
        $builder->WHEREIn('motors.motor_driver_id', $value);
    }



    /**
     * MOTOR TYPES
     */
    public function motorTypes(Builder $builder, array $types)
    {
        $builder->WHEREIn('motors.motor_type_id', $types);
    }



    /**
     * HAS OPTION
     */
    public function hasOptions(Builder $builder, bool $options)
    {
        if($options)
            $builder->WHERENotNull('car_options.car_id');
        else
            $builder->WHERENull('car_options.car_id');
    }



    /**
     * НАЛИЧИЕ ПЕРЕОЦЕНКИ
     */
    public function hasOverPrice(Builder $builder, bool $value)
    {
        if($value)
            $builder->WHERE('overprice.price', '>', 0);
        else
            $builder->WHERE(function($query) {
                $query->WHERENull('overprice.price')
                    ->orWHERE('overprice.price', 0);
            });
    }



    /**
     * НАЛИЧИЕ ТЮНИНГА
     */
    public function hasTuning(Builder $builder, bool $value)
    {
        if($value)
            $builder->WHERENotNull('tuning.price');
        else
            $builder->WHERENull('tuning.price');
    }



    /**
     * HAS DEVICES
     */
    public function hasDevices(Builder $builder, bool $device)
    {
        if($device)
            $builder->WHERENotNull('car_tunings.car_id');
        else
            $builder->WHERENull('car_tunings.car_id');
    }



    /**
     * НАЛИЧИЕ ПОДАРКА
     */
    public function hasGift(Builder $builder, bool $value)
    {
        if($value)
            $builder->WHERENotNull('gift.price');
        else
            $builder->WHERENull('gift.price');
    }



    /**
     * НАЛИЧИЕ СКИДКИ
     */
    public function hasDiscount(Builder $builder, bool $value)
    {
        if($value)
            $builder->WHERENotNull('discounts.id');
        else
            $builder->WHERENull('discounts.id');
    }



    /**
     * НАЛИЧИЕ ВЫКУПНОГО ПЛАТЕЖА
     */
    public function hasRansom(Builder $builder, bool $purchase)
    {
        if($purchase)
            $builder->WHERENotNull('ransom_cars.car_id');
        else
            $builder->WHERENull('ransom_cars.car_id');
    }



    /**
     * НАЛИЧИЕ ДОП СЕБЕСТОИМОСТИ
     */
    public function hasDetailingCost(Builder $builder, bool $value)
    {
        if($value)
            $builder->WHERENotNull('car_detailing_costs.id');
        else
            $builder->WHERENull('car_detailing_costs.id');
    }



    /**
     * Логистический статус (В заказе, в отгрузке и тд)
     */
    public function stateStatus(Builder $builder, array $value)
    {
        $baseState = CarState::pluck('status')->toArray();

        $arr = array_intersect($baseState, $value);

        $builder->WHEREIn('cars.status', $arr);
    }



    /**
     * Товарный признак
     */
    public function tradeMarkerId(Builder $builder, string|array $value)
    {
        if(is_string($value))
            $value = [$value];
        $builder->WHEREIn('car_trade_markers.trade_marker_id', $value);
    }



    /**
     * КонтрМарка
     */
    public function markerId(Builder $builder, string|array $value)
    {
        if(is_string($value))
            $value = [$value];
        $builder->WHEREIn('car_markers.marker_id', $value);
    }



    /**
     * ИНТЕРВАЛ ПОД ДАТУ СОЗДАНИЯ РЕЗЕРВА
     */
    public function reserveDate(Builder $builder, array $date)
    {
        // $date_1 = Carbon::createFromFormat('d.m.Y', $date[0])->format('Y-m-d');
        // $date_2 = isset($date[1]) ? Carbon::createFromFormat('d.m.Y', $date[1])->format('Y-m-d') : $date_1;
        $dates = DateHelper::setDateToCarbon($date, 'd.m.Y');

        $builder->WHEREBetween('wsm_reserve_new_cars.created_at', $dates);
    }



    /**
     * НАЛИЧИЕ КОНТРАКТА
     */
    public function hasContract(Builder $builder, bool $val)
    {
        if($val)
            $builder->WHERENotNull('contract.id');
        else
            $builder->WHERENull('contract.id');
    }



    /**
     * ПЕРВИЧНЫЙ КОНТРАКТ
     */
    public function contractDate(Builder $builder, array $date)
    {
        $date_1 = Carbon::createFromFormat('d.m.Y', $date[0])->format('Y-m-d');
        $date_2 = isset($date[1]) ? Carbon::createFromFormat('d.m.Y', $date[1])->format('Y-m-d') : $date_1;
        $builder->WHERERaw(
            'IF(contract.pdkp_offer_at IS NOT NULL,'. 
            'contract.pdkp_offer_at BETWEEN "'.$date_1.'" and "'.$date_2.'",'.
            'contract.dkp_offer_at BETWEEN "'.$date_1.'" and "'.$date_2.'")'
        );
    }



    /**
     * DKP
     */
    public function dkpDate(Builder $builder, array $date)
    {
        // $date_1 = Carbon::createFromFormat('d.m.Y', $date[0])->format('Y-m-d');
        // $date_2 = isset($date[1]) ? Carbon::createFromFormat('d.m.Y', $date[1])->format('Y-m-d') : $date_1;
        $dates = DateHelper::setDateToCarbon($date, 'd.m.Y');

        $builder->WHEREBetween('contract.dkp_offer_at', $dates);
    }



    /**
     * PDKP
     */
    public function pdkpDate(Builder $builder, array $date)
    {
        // $date_1 = Carbon::createFromFormat('d.m.Y', $date[0])->format('Y-m-d');
        // $date_2 = isset($date[1]) ? Carbon::createFromFormat('d.m.Y', $date[1])->format('Y-m-d') : $date_1;
        $dates = DateHelper::setDateToCarbon($date, 'd.m.Y');

        $builder->WHEREBetween('contract.pdkp_offer_at', $dates);
    }



    /**
     * ОПЛАТА КЛИЕНТА
     */
    public function hasClientPay(Builder $builder, bool $val)
    {
        if($val)
            $builder->WHERENotNull('wsm_reserve_payments.id');
        else
            $builder->WHERENull('wsm_reserve_payments.id');
    }



    /**
     * Выдача
     */
    public function hasIssue(Builder $builder, bool $val)
    {
        if($val)
            $builder->WHERENotNull('wsm_reserve_issues.id');
        else
            $builder->WHERENull('wsm_reserve_issues.id');
    }



    /**
     * Продажа
     */
    public function hasSale(Builder $builder, bool $val)
    {
        if($val)
            $builder->WHERENotNull('wsm_reserve_sales.id');
        else
            $builder->WHERENull('wsm_reserve_sales.id');
    }



    /**
     * Дата продажи
     */
    public function saleDate(Builder $builder, array $date)
    {
        $dates = DateHelper::setDateToCarbon($date, 'd.m.Y');
        
        $builder->WHEREBetween('wsm_reserve_sales.date_at', $dates);
    }



    /**
     * НАЛИЧИЕ СПИСАНИЯ
     */
    public function hasOff(Builder $builder, bool $value)
    {
        if($value)
            $builder->WHERENotNull('car_owners.id');
        else
            $builder->WHERENull('car_owners.id');
    }



    /**
     * ТИП РАПОРТА
     */
    public function reportType(Builder $builder, array $reportTypes)
    {
        $builder->WHERE(function($query) use($reportTypes) {
                $query->WHERE(function($reportQuery) use($reportTypes){
                    foreach($reportTypes as $index => $type)
                        switch($type){
                            case '1':
                                $reportQuery->WHERE(function($green) {
                                    $green->WHERE('worksheets.client_id', DB::raw('car_owners.client_id'))
                                        ->WHERENotNull('car_owners.client_id');
                                });
                                break;
                            case '2':
                                $reportQuery->WHERE(function($yellow) {
                                    $yellow->WHERE('worksheets.client_id', '<>', DB::raw('car_owners.client_id'))
                                        ->orWHERENull('worksheets.client_id')
                                        ->WHERENotNull('car_owners.client_id');
                                });
                                break;
                            default:
                                break;
                        };
                });
        });
    }



    // public function offDate(Builder $builder, array $date)
    // {
    //     $date_1 = Carbon::createFromFormat('d.m.Y', $date[0])->format('Y-m-d');
    //     $date_2 = isset($date[1]) ? Carbon::createFromFormat('d.m.Y', $date[1])->format('Y-m-d') : $date_1;
    //     $builder->WHEREBetween('car_date_logistics.date_at', [$date_1, $date_2])
    //         ->WHERE('car_date_logistics.logistic_system_name', 'off_date');
    // }



    /**
     * ВСЕ ЛОГИСТИЧЕСКИЕ ДАТЫ
     */
    public function logisticDates(Builder $builder, array $data)
    {   
        if(!count($data))
            return;
        
        $builder->WHERE(function($dateQuery) use($data){
            foreach($data as $key => $dateInterval)
                $dateQuery->orWHERE(function($builderOrderDate) use ($key, $dateInterval){
                    //$date_1 = Carbon::createFromFormat('d.m.Y', $dateInterval[0])->format('Y-m-d');
                    //$date_2 = isset($dateInterval[1]) ? Carbon::createFromFormat('d.m.Y', $dateInterval[1])->format('Y-m-d') : $date_1;
                    $dates = DateHelper::setDateToCarbon($dateInterval, 'd.m.Y');

                    $builderOrderDate->WHERE('car_date_logistics.logistic_system_name', $key)
                        ->WHEREBetween('car_date_logistics.date_at', $dates);
                });
        });
    }



    /**
     * НАЛИЧИЕ В ПЛАНЕ ПОСТАВЩИКА
     */
    public function hasPlan(Builder $builder, bool $value)
    {
        if($value)
            $builder->WHERE('cars.disable_off', 0);
        else   
            $builder->WHERE('cars.disable_off', 1); 
    }



    /**
     * COLOR
     */
    public function colors(Builder $builder, array $colors)
    {
        $builder->WHEREIn('dealer_colors.base_id', $colors);
    }



    public function search(Builder $builder, string $value)
    {
        $value = str_contains($value, '@') ? $value : '@'.$value;
        $value = explode('@', $value);
        list($column, $val) = $value;

        switch($column){
            case 'client':
                $builder->WHERE(function($subQ) use ($val){
                    $subQ->WHERE('clients.lastname', 'LIKE', '%'.$val.'%');
                    $subQ->orWHERE('clients.company_name', 'LIKE', '%'.$val.'%');
                });
                break;
            case 'id':
                $builder->WHERE('cars.id', $val);
                break;
            case 'vin':
                $builder->WHERE('cars.vin', 'LIKE', '%' . $val . '%');
                break;
            case 'order':
                $builder->WHERE('car_orders.order_number', 'LIKE', '%' . $val . '%');
                break;
            default:
                $builder->WHERE(function ($query) use ($val) {
                    $query->WHERE('cars.vin',                   'LIKE', '%' . $val . '%')
                        ->orWHERE('cars.id',                    'LIKE', '%' . $val . '%')
                        ->orWHERE('car_orders.order_number',    'LIKE', '%' . $val . '%');
                });
                break;
        }
    }

}
