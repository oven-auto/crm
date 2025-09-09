<?php

namespace App\Http\Filters;

use App\Models\CarState;
use App\Models\CarStatusType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Schema(
 *   description = "Параметры фильтрации автосклада"
 * )
 */
class CarFilter extends AbstractFilter
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
    public const BRAND_ID = 'brands';//Бренд

    /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы моделей", 
     * property="models", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const MARK_ID = 'models';//Модель

    /**  @OA\Property(
     * format="integer", 
     * description="Год выпуска", 
     * property="year", 
     * type="integer", 
     * example="2024")
     * */
    public const YEAR = 'year';//Год выпуска

    /**  @OA\Property(
     * format="string", 
     * description="VIN", 
     * property="vin", 
     * type="string", 
     * example="XTAGFK350R0836729")
     * */
    public const VIN = 'vin';//ВИН

    /**  @OA\Property(
     * format="string", 
     * description="Код комплектации", 
     * property="complectation_code", 
     * type="string", 
     * example="BGT04-000-61")
     * */
    public const COMPLECTATION_CODE = 'complectation_code';//Код комплектации

    /**  @OA\Property(
     * format="integer", 
     * description="Идентификатор комплектации", 
     * property="complectation_id", 
     * type="integer", 
     * example="1")
     * */
    public const COMPLECTATION_ID = 'complectation_id';//ИД Комплектации

    /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы типов кузова", 
     * property="bodyworks", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const BODY = 'bodyworks';//Кузов

    /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы типов трансмиссий", 
     * property="transmissions", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const TRANSMISSION = 'transmissions';//Тип КПП

    /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы типов двигателя", 
     * property="motortypes", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const MOTOR_TYPE = 'motortypes';//Тип КПП

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
     * format="string", 
     * description="Номер заказа", 
     * property="order_number", 
     * type="string", 
     * example="1122")
     * */
    public const ORDER_NUMBER = 'order_number';//Номер заказа

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
     * description="Массив содержащий идентификаторы автомобилей", 
     * property="ids", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const IDS = 'ids';//Указанные ИД машин

    /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификатор статуса автомобиля", 
     * property="type_statuses", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const TYPE_STATUS = 'type_statuses';//Статус автомобиля

    /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы типов привода", 
     * property="drivers", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const DRIVER = 'drivers';//Тип привода

    /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы техника-приемщика", 
     * property="technics", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const TECHNIC = 'technics';//Техник

    /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы условий поставки", 
     * property="delivery_terms", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const DELIVERY_TERMS = 'delivery_terms';//Условия поставки

     /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий интервал дат начало платного периода от - до, параметр ДО необязателен, 
     * отсутствие второго параметра, будет означать, что используется не интервал, 
     * соответственно сравнение будет строго по одному параметру", 
     * property="paid_dates", 
     * type="array", 
     * example="[01.10.2024,22.10.2024]", 
     * @OA\Items())
     * */
    public const PAID_DATE = 'paid_dates';//Начало платного периода

    /**  @OA\Property(
     *      format="bool", 
     *      description="Наличие начало платного периода .", 
     *      property="has_paid_date", 
     *      type="bool", 
     *      example="1")
     * */
    public const HAS_PAID_DATE = 'has_paid_date';

    /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий интервал дат контроля срока оплаты от - до, параметр ДО необязателен, 
     * отсутствие второго параметра, будет означать, что используется не интервал, 
     * соответственно сравнение будет строго по одному параметру", 
     * property="control_paid_dates", 
     * type="array", 
     * example="[01.10.2024,22.10.2024]", 
     * @OA\Items())*/
    public const CONTROLL_PAID_DATE = 'control_paid_dates';//Контрольный срок оплаты

    /**  @OA\Property(
     *      format="bool", 
     *      description="Наличие контроля срока оплаты .", 
     *      property="has_controll_paid_date", 
     *      type="bool", 
     *      example="1")
     * */
    public const HAS_CONTROLL_PAID_DATE = 'has_controll_paid_date';

    /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы держателей залога", 
     * property="collectors", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const COLLECTOR = 'collectors';//Держатель залога

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
    public const PRICES = 'prices';

    /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий интервал даты этапа заявка от - до, параметр ДО необязателен, 
     * отсутствие второго параметра, будет означать, что используется не интервал, 
     * соответственно сравнение будет строго по одному параметру", 
     * property="application_date", 
     * type="array", 
     * example="[01.10.2024,22.10.2024]", 
     * @OA\Items())
     * */
    public const APPLICATION_DATE = 'application_date';//Все логистические даты(используется мидлвар)

     /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий интервал даты этапа в заказе от - до, параметр ДО необязателен, 
     * отсутствие второго параметра, будет означать, что используется не интервал, 
     * соответственно сравнение будет строго по одному параметру", 
     * property="order_date", 
     * type="array", 
     * example="[01.10.2024,22.10.2024]", 
     * @OA\Items())
     * */
    public const ORDER_DATE = 'order_date';//Все логистические даты(используется мидлвар)

     /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий интервал даты этапа сборка планируемая от - до, параметр ДО необязателен, 
     * отсутствие второго параметра, будет означать, что используется не интервал, 
     * соответственно сравнение будет строго по одному параметру", 
     * property="plan_date", 
     * type="array", 
     * example="[01.10.2024,22.10.2024]", 
     * @OA\Items())
     * */
    public const PLAN_DATE = 'plan_date';//Все логистические даты(используется мидлвар)

     /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий интервал даты этапа сборка фактическая от - до, параметр ДО необязателен, 
     * отсутствие второго параметра, будет означать, что используется не интервал, 
     * соответственно сравнение будет строго по одному параметру", 
     * property="build_date", 
     * type="array", 
     * example="[01.10.2024,22.10.2024]", 
     * @OA\Items())
     * */
    public const BUILD_DATE = 'build_date';//Все логистические даты(используется мидлвар)

     /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий интервал даты этапа готовность к отгрузке от - до, параметр ДО необязателен, 
     * отсутствие второго параметра, будет означать, что используется не интервал, 
     * соответственно сравнение будет строго по одному параметру", 
     * property="ready_date", 
     * type="array", 
     * example="[01.10.2024,22.10.2024]", 
     * @OA\Items())
     * */
    public const READY_DATE = 'ready_date';//Все логистические даты(используется мидлвар)

     /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий интервал даты этапа заявка на перевозку от - до, параметр ДО необязателен, 
     * отсутствие второго параметра, будет означать, что используется не интервал, 
     * соответственно сравнение будет строго по одному параметру", 
     * property="request_date", 
     * type="array", 
     * example="[01.10.2024,22.10.2024]", 
     * @OA\Items())
     * */
    public const REQUEST_DATE = 'request_date';//Все логистические даты(используется мидлвар)

     /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий интервал даты этапа отгрузка от - до, параметр ДО необязателен, 
     * отсутствие второго параметра, будет означать, что используется не интервал, 
     * соответственно сравнение будет строго по одному параметру", 
     * property="shipment_date", 
     * type="array", 
     * example="[01.10.2024,22.10.2024]", 
     * @OA\Items())
     * */
    public const SHIPMENT_DATE = 'shipment_date';//Все логистические даты(используется мидлвар)

     /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий интервал даты этапа приемка на склад от - до, параметр ДО необязателен, 
     * отсутствие второго параметра, будет означать, что используется не интервал, 
     * соответственно сравнение будет строго по одному параметру", 
     * property="stock_date", 
     * type="array", 
     * example="[01.10.2024,22.10.2024]", 
     * @OA\Items())
     * */
    public const STOCK_DATE = 'stock_date';//Все логистические даты(используется мидлвар)

    public const HAS_STOCK_DATE = 'has_stock_date';

     /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий интервал даты этапа приходная накладная от - до, параметр ДО необязателен, 
     * отсутствие второго параметра, будет означать, что используется не интервал, 
     * соответственно сравнение будет строго по одному параметру", 
     * property="invoice_date", 
     * type="array", 
     * example="[01.10.2024,22.10.2024]", 
     * @OA\Items())
     * */
    public const INVOICE_DATE = 'invoice_date';//Все логистические даты(используется мидлвар)

     /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий интервал даты этапа оплата поставщику от - до, параметр ДО необязателен, 
     * отсутствие второго параметра, будет означать, что используется не интервал, 
     * соответственно сравнение будет строго по одному параметру", 
     * property="ransom_date", 
     * type="array", 
     * example="[01.10.2024,22.10.2024]", 
     * @OA\Items())
     * */
    public const RANSOM_DATE = 'ransom_date';//Все логистические даты(используется мидлвар)

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
     *      format="array", 
     *      description="Массив содержащий интервал даты этапа предпродажная подготовка от - до, параметр ДО необязателен, 
     *      отсутствие второго параметра, будет означать, что используется не интервал, 
     *      соответственно сравнение будет строго по одному параметру", 
     *      property="presale_date", 
     *      type="array", 
     *      example="[01.10.2024,22.10.2024]", 
     *      @OA\Items()
     * )
     * */
    public const PRESALE_DATE = 'presale_date';//Все логистические даты(используется мидлвар)

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
     * description="Наличие залога, 1 - есть, 0 нет.", 
     * property="has_deposit", 
     * type="bool", 
     * example="1")
     * */
    public const HAS_DEPOSIT = 'has_deposit';

    /**  @OA\Property(
     * format="bool", 
     * description="Наличие списания, 1 - есть, 0 нет.", 
     * property="has_off", 
     * type="bool", 
     * example="1")
     * */
    public const HAS_OFF = 'has_off';

    /**  @OA\Property(
     * format="bool", 
     * description="Наличие дополнительной себестоимости, 1 - есть, 0 нет.", 
     * property="has_detailing_cost", 
     * type="bool", 
     * example="1")
     * */
    public const HAS_DETAILING_COST = 'has_detailing_cost';

    /**  @OA\Property(
     * format="bool", 
     * description="Наличие в плане поставщика, 1 - есть, 0 нет.", 
     * property="has_plan", 
     * type="bool", 
     * example="1")
     * */
    public const HAS_PLAN = 'has_plan';

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
     * format="bool", 
     * description="Наличие установленого тюнинга(номенклатурно)", 
     * property="has_devices", 
     * type="bool"
     * )
     * */
    public const HAS_DEVICES = 'has_devices';

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
     * description="Наличие опций", 
     * property="has_options", 
     * type="bool"
     * )
     * */
    public const HAS_OPTIONS = 'has_options';

    /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы цвета (БАЗОВЫЕ ЦВЕТА - НЕ ДИЛЕРСКИЕ)", 
     * property="colors", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const COLOR = 'colors';

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
     *      format="array", 
     *      description="Массив содержащий интервал мощности мотора от - до, параметр ДО необязателен, 
     *      отсутствие второго параметра, будет означать, что используется не интервал, 
     *      соответственно сравнение будет строго по одному параметру", 
     *      property="power", 
     *      type="array", 
     *      example="[140,150]", 
     *      @OA\Items()
     * )
     * */
    public const POWER = 'power';

    /**
     * @OA\Property(
     *  format="string", 
     *  description="Сортировка: price_low, price_high, sale_old, sale_new, stock_old, stock_new", 
     *  property="sort", 
     *  type="string"
     * )
     */
    public const SORT = 'sort';

        /**
     * @OA\Property(
     *  format="bool", 
     *  description="Только свободные", 
     *  property="only_free", 
     *  type="bool"
     * )
     */
    public const ONLY_FREE = 'only_free';


     /**  @OA\Property(
     *      format="array", 
     *      description="Массив содержащий интервал даты этапа продажа от - до, параметр ДО необязателен, 
     *      отсутствие второго параметра, будет означать, что используется не интервал, 
     *      соответственно сравнение будет строго по одному параметру", 
     *      property="sale_date", 
     *      type="array", 
     *      example="[01.10.2024,22.10.2024]", 
     *      @OA\Items()
     * )
     * */
    public const SALE_DATE = 'sale_date';

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

    public const LOGISTIC_DATES = 'logistic_dates';

    public const INIT = 'init';

    public const INPUT = 'input';

    /**
     * @OA\Property(
     *  format="bool", 
     *  description="Удаленные 1 - только, 0 - не удаленные", 
     *  property="trashed", 
     *  type="bool"
     * )
     */
    public const TRASHED = 'trashed';



    protected function getCallbacks(): array
    {
        return [
            self::INIT                  => [$this, 'init'],
            self::INPUT                 => [$this, 'input'],
            self::BRAND_ID              => [$this, 'brandIds'],
            self::MARK_ID               => [$this, 'markIds'],
            self::YEAR                  => [$this, 'year'],
            self::VIN                   => [$this, 'vin'],
            self::COMPLECTATION_CODE    => [$this, 'complectationCode'],
            self::COMPLECTATION_ID      => [$this, 'complectationId'],
            self::BODY                  => [$this, 'bodyWorks'],
            self::TRANSMISSION          => [$this, 'transmission'],
            self::TRADE_MARKER_ID       => [$this, 'tradeMarkerId'],
            self::MARKER_ID             => [$this, 'markerId'],
            self::ORDER_NUMBER          => [$this, 'orderNumber'],
            self::STATE_STATUS          => [$this, 'stateStatus'],
            self::SEARCH                => [$this, 'search'],
            self::IDS                   => [$this, 'ids'],
            self::TYPE_STATUS           => [$this, 'typeStatus'],
            self::DRIVER                => [$this, 'drivers'],
            self::LOGISTIC_DATES        => [$this, 'logisticDates'],
            self::TECHNIC               => [$this, 'technic'],
            self::DELIVERY_TERMS        => [$this, 'deliveryTerm'],
            self::PAID_DATE             => [$this, 'paidDate'],
            self::CONTROLL_PAID_DATE    => [$this, 'controllPaidDate'],
            self::COLLECTOR             => [$this, 'collector'],
            self::PRICES                => [$this, 'prices'],
            self::HAS_OVERPRICE         => [$this, 'hasOverPrice'],
            self::HAS_TUNING            => [$this, 'hasTuning'],
            self::HAS_GIFT              => [$this, 'hasGift'],
            self::HAS_DISCOUNT          => [$this, 'hasDiscount'],
            self::HAS_DEPOSIT           => [$this, 'hasDeposit'],
            self::HAS_OFF               => [$this, 'hasOff'],
            self::HAS_DETAILING_COST    => [$this, 'hasDetailingCost'],
            self::HAS_PLAN              => [$this, 'hasPlan'],
            self::REPORT_TYPE           => [$this, 'reportType'],
            self::HAS_DEVICES           => [$this, 'hasDevices'],
            self::HAS_RANSOM            => [$this, 'hasRansom'],
            self::HAS_OPTIONS           => [$this, 'hasOptions'],
            self::MOTOR_TYPE            => [$this, 'motorTypes'],
            self::COLOR                 => [$this, 'colors'],
            self::HAS_SALE              => [$this, 'hasSale'],
            self::POWER                 => [$this, 'power'],
            self::SORT                  => [$this, 'sort'],
            self::SALE_DATE             => [$this, 'saleDate'],
            self::HAS_PAID_DATE         => [$this, 'hasPaidDate'],
            self::CONTROLL_PAID_DATE    => [$this, 'hasControllPaidDate'],
            self::ONLY_FREE             => [$this, 'onlyFree'],
            self::PRIORITY_IDS          => [$this, 'priorityIds'],
            self::HAS_PRIORITY          => [$this, 'hasPriority'],
            self::HAS_STOCK_DATE        => [$this, 'hasStockDate'],
            //self::OFF_DATE              => [$this, 'offDate'],
            self::TRASHED               => [$this, 'trashed'],
        ];
    }



    public function trashed(Builder $builder, bool $val)
    {
        match($val){
            true => $builder->onlyTrashed(),
            default => ''
        };
    }



    public function sort(Builder $builder, $val)
    {
        match($val){
            'price_low'     => $builder
                ->orderBy('cfp.complectationprice', 'ASC'),
            'price_high'    => $builder
                ->orderBy('cfp.complectationprice', 'DESC'),
            'sale_old'      => $builder
                ->orderBy(DB::raw('IF(wsm_reserve_sales.id IS NOT NULL, 0, 1)'))
                ->orderBy('wsm_reserve_sales.date_at', 'ASC'),
            'sale_new'      => $builder
                ->orderBy(DB::raw('IF(wsm_reserve_sales.id IS NOT NULL, 0, 1)'))
                ->orderBy('wsm_reserve_sales.date_at', 'DESC'),
            'stock_old'     => $builder
                ->orderBy(DB::raw('IF(stocking_date IS NOT NULL, 0, 1)'))
                ->orderBy('stocking_date', 'ASC'),
            'stock_new'     => $builder
                ->orderBy(DB::raw('IF(stocking_date IS NOT NULL, 0, 1)'))
                ->orderBy('stocking_date', 'DESC'),
            'name_asc' => $builder
                ->orderBy('brands.name', 'ASC')
                ->orderBy('marks.name', 'ASC'),
            'name_desc' => $builder
                ->orderBy('brands.name', 'DESC')
                ->orderBy('marks.name', 'DESC'),
            default => '',
        };
    }



    public function input(Builder $builder, $val)
    {
        $builder->where('cars.id', 'LIKE', '%'.$val.'%');
    }



    public function __construct(Builder $builder, array $queryParams)
    {   
        $queryParams['init'] = $queryParams;
        
        parent::__construct($queryParams);        
    }



    public function init(Builder $builder, array $params)
    {
        if(isset($queryParams['having']) && $queryParams['having'] > 0)
            $builder->havingRaw('count(cars.id) > '.($queryParams['having']-1));

        $this->setJoinForSearch($builder, $params);

        $builder
            ->addSelect(DB::raw('(SELECT cdl.date_at FROM car_date_logistics cdl WHERE cdl.logistic_system_name = "stock_date" and cdl.car_id = cars.id) as stocking_date'));

        if(!isset($params['sort']))
            $builder->orderBy('id', 'DESC');
    }



    public function setJoinForSearch(Builder $builder, array $params)
    {   
        //if(isset($params['prices']) || isset($params['has_discount']) || isset($params['report_type']) || isset($params['has_sale']))
            $builder->leftJoin('wsm_reserve_new_cars as reserve', function($join){
                $join->on('reserve.car_id', 'cars.id')
                    ->whereNull('reserve.deleted_at');
            });//резерв авто

        //if(isset($params['has_sale']))
            $builder->leftJoin('wsm_reserve_sales', 'wsm_reserve_sales.reserve_id', 'reserve.id');
        
        //if(isset($params['has_discount']) || isset($params['report_type']))
            $builder->leftJoin('worksheets', 'worksheets.id', 'reserve.worksheet_id');

        //if(isset($params['brands']))//brand
            $builder->leftJoin('brands', 'brands.id', 'cars.brand_id'); 

        //if(isset($params['models']))//model
            $builder->leftJoin('marks', 'marks.id', 'cars.mark_id');
        
        // if(isset($params['complectation_code']) || //complectation_code
        //     isset($params['transmissions']) || //transmissions
        //     isset($params['drivers']) || //drivers
        //     isset($params['motortypes']) || //motortypes
        //     isset($params['bodyworks']) || //bodywork
        //     isset($params['power']) //bodywork
        // )
            $builder
                ->leftJoin('complectations', 'complectations.id', 'cars.complectation_id')
                ->leftJoin('motors', 'motors.id', 'complectations.motor_id');

        //if(isset($params['trade_markers']))//trade_marker
            $builder->leftJoin('car_trade_markers', 'car_trade_markers.car_id', 'cars.id');

        //if(isset($params['markers']))//markers
            $builder->leftJoin('car_markers', 'car_markers.car_id', 'cars.id');

        //if(isset($params['order_number']) || isset($params['search']))//order_number;
            $builder->leftJoin('car_orders', 'car_orders.car_id', 'cars.id');

        //if(isset($params['logistic_dates']) && count($params['logistic_dates']))//логистика
            $builder->leftJoin('car_date_logistics', 'car_date_logistics.car_id', 'cars.id');

        //if(isset($params['type_statuses']))//car_status_type
            $builder->leftJoin('car_status_types', 'car_status_types.car_id', 'cars.id');

        //if(isset($params['technics']))//technics
            $builder->leftJoin('car_technics', 'car_technics.car_id', 'cars.id');

        //if(isset($params['paid_dates']))//кредитный период
            $builder->leftJoin('car_paid_dates', 'car_paid_dates.car_id', 'cars.id');

        //if(isset($params['control_paid_dates']))//контроль оплаты
            $builder->leftJoin('car_controll_paid_dates', 'car_controll_paid_dates.car_id', 'cars.id');

        // if(
        //     isset($params['collectors']) ||//collectors
        //     isset($params['has_deposit']) //has_deposit
        // )
            $builder->leftJoin('car_collectors', 'car_collectors.car_id', 'cars.id');

        //if(isset($params['delivery_terms']))//delivery
            $builder->leftJoin('car_delivery_terms', 'car_delivery_terms.car_id', 'cars.id');

        //if(isset($params['colors']))//colors
            $builder->leftJoin('dealer_colors', 'dealer_colors.id', 'cars.color_id');

        //if(isset($params['has_ransom']))//has_ransom
            $builder->leftJoin('ransom_cars', 'ransom_cars.car_id', 'cars.id'); 

        //if(isset($params['has_options']))//has_option
            $builder->leftJoin('car_options', 'car_options.car_id', 'cars.id');

        // if(
        //     isset($params['has_off']) || //has_off
        //     isset($params['report_type']) //report_type
        // )
            $builder->leftJoin('car_owners', 'car_owners.car_id', 'cars.id');

        //if(isset($params['has_detailing_cost']))//has_detailing_cost
            $builder->leftJoin('car_detailing_costs', 'car_detailing_costs.car_id', 'cars.id');

        //if(isset($params['has_devices']))//has_devices
            $builder->leftJoin('car_tunings', 'car_tunings.car_id', 'cars.id');

        //if(isset($params['prices']) || isset($params['has_overprice']))
            $builder->leftJoin('car_over_prices as overprice', 'overprice.car_id', 'cars.id')
                ->addSelect([
                    'overprice.price            as _over_price',
                ]);

        //if(isset($params['prices']) || isset($params['has_tuning']))
            $builder->leftJoin('car_tuning_prices as tuning', 'tuning.car_id', 'cars.id')//цена тюнинга
                ->addSelect([
                    'tuning.price               as _tuning_price',
                ]);

        //if(isset($params['prices']) || isset($params['has_gift']))
            $builder->leftJoin('car_gift_prices as gift', 'gift.car_id', 'cars.id')//gift
                ->addSelect([
                    'gift.price                 as _gift_price', 
                ]);

        //if(isset($params['prices']))//prices
            $builder ->leftJoin('car_full_prices as cfp', 'cfp.car_id', 'cars.id')//представление хранящее актуальную цену авто по прайсу
                ->leftJoin('wsm_reserve_new_car_contracts as contract', 'contract.reserve_id', 'reserve.id')//контракт резерва
                ->leftJoin('wsm_reserve_complectation_prices as wrcp','wrcp.contract_id', 'contract.id')//сохраненая в контракте цена
                ->leftJoin('complectation_prices as cp', 'cp.id', 'wrcp.complectation_price_id')//цены комплектации
                ->leftJoin('wsm_reserve_option_prices as wrop', 'wrop.contract_id', 'contract.id')//сохраненные в контракте опции
                
                
                
                // ->leftJoin(DB::raw('(SELECT sum(option_prices.price) as sum_option, wsm_reserve_new_cars.car_id from option_prices 
                //     left join wsm_reserve_option_prices on wsm_reserve_option_prices.option_price_id = option_prices.id 
                //     left join wsm_reserve_new_car_contracts on wsm_reserve_new_car_contracts.id = wsm_reserve_option_prices.contract_id 
                //     left join wsm_reserve_new_cars on wsm_reserve_new_cars.id = wsm_reserve_new_car_contracts.reserve_id 
                //     where wsm_reserve_new_cars.car_id is not null and wsm_reserve_new_cars.deleted_at is not null
                //     GROUP  BY  wsm_reserve_new_cars.car_id) as joinOptionPrice'), 'joinOptionPrice.car_id', 'cars.id'
                // )

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

        //if(isset($params['has_discount']))
            $builder->leftJoin('discounts', function($join){
                    $join->on('discounts.modulable_type', '=', DB::raw('"App\\\Models\\\WsmReserveNewCar"'));
                    $join->on('discounts.modulable_id', 'reserve.id');
            });

            $builder->leftJoin('car_sale_priorities', 'car_sale_priorities.car_id', 'cars.id');
            
            $builder->leftJoin('wsm_reserve_lisings', 'wsm_reserve_lisings.reserve_id', 'reserve.id');

        $builder            
            ->groupBy('cars.id');            
    }



    public function priorityIds(Builder $builder, array $arr)
    {
        $builder->whereIn('car_sale_priorities.priority_id', $arr);
    }



    public function hasPriority(Builder $builder, bool $val)
    {
        match($val){
            true => $builder->whereNotNull('car_sale_priorities.priority_id'),
            false => $builder->whereNull('car_sale_priorities.priority_id'),
            default => null,
        };
    }



    public function onlyFree(Builder $builder, $val)
    {
        if($val)
            $builder->where('car_status_types.status', 'free');
    }



    public function hasPaidDate(Builder $builder, bool $val)
    {
        if($val)
            $builder->whereNotNull('car_paid_dates.date_at');
        else
            $builder->whereNull('car_paid_dates.date_at');
    }



    public function hasControllPaidDate(Builder $builder, bool $val)
    {
        if($val)
            $builder->whereNotNull('car_controll_paid_dates.date_at');
        else
            $builder->whereNull('car_controll_paid_dates.date_at');
    }



    public function hasStockDate(Builder $builder, bool $val)
    {
        // $builder->where(function($query){
        //     $query->where('car_date_logistics.logistic_system_name', 'stock_date')
        //         ->whereNotNull('car_date_logistics.date_at');
        // });
        $builder->where('cars.status', 'in_stock');
    }



    /**
     * Дата продажи
     */
    public function saleDate(Builder $builder, array $date)
    {
        $date_1 = Carbon::createFromFormat('d.m.Y', $date[0])->format('Y-m-d');
        $date_2 = isset($date[1]) ? Carbon::createFromFormat('d.m.Y', $date[1])->format('Y-m-d') : $date_1;
        $builder->whereBetween('wsm_reserve_sales.date_at', [$date_1, $date_2]);
    }



    public function power(Builder $builder, array $power)
    {
        if(count($power) == 1)
            $power[] = $power[0];
        $builder->whereBetween('motors.power', $power);
    }



    public function hasSale(Builder $builder, $val)
    {
        if($val)
            $builder->whereNotNull('wsm_reserve_sales.id');
        else
            $builder->whereNull('wsm_reserve_sales.id');
    }



    public function colors(Builder $builder, array $colors)
    {
        $builder->whereIn('dealer_colors.base_id', $colors);
    }



    public function motorTypes(Builder $builder, array $types)
    {
        $builder->whereIn('motors.motor_type_id', $types);
    }



    public function hasOptions(Builder $builder, bool $options)
    {
        if($options)
            $builder->whereNotNull('car_options.car_id');
        else
            $builder->whereNull('car_options.car_id');
    }



    public function hasRansom(Builder $builder, bool $purchase)
    {
        if($purchase)
            $builder->whereNotNull('ransom_cars.car_id');
        else
            $builder->whereNull('ransom_cars.car_id');
    }



    public function hasDevices(Builder $builder, bool $device)
    {
        if($device)
            $builder->whereNotNull('car_tunings.car_id');
        else
            $builder->whereNull('car_tunings.car_id');
        
    }



    /**
     * ТИП РАПОРТА
     */
    public function reportType(Builder $builder, array $reportTypes)
    {
        $builder->where(function($query) use($reportTypes) {
                $query->where(function($reportQuery) use($reportTypes){
                    foreach($reportTypes as $index => $type)
                        switch($type){
                            case '1':
                                $reportQuery->where(function($green) {
                                    $green->where('worksheets.client_id', DB::raw('car_owners.client_id'))
                                        ->whereNotNull('car_owners.client_id');
                                });
                                break;
                            case '2':
                                $reportQuery->where(function($yellow) {
                                    $yellow->where('worksheets.client_id', '<>', DB::raw('car_owners.client_id'))
                                        ->orWhereNull('worksheets.client_id')
                                        ->whereNotNull('car_owners.client_id');
                                });
                                break;
                            default:
                                break;
                        };
                });
        });
    }



    /**
     * НАЛИЧИЕ В ПЛАНЕ ПОСТАВЩИКА
     */
    public function hasPlan(Builder $builder, bool $value)
    {
        if($value)
            $builder->where('cars.disable_off', 0);
        else   
            $builder->where('cars.disable_off', 1); 
    }



    /**
     * НАЛИЧИЕ ДОП СЕБЕСТОИМОСТИ
     */
    public function hasDetailingCost(Builder $builder, bool $value)
    {
        if($value)
            $builder->whereNotNull('car_detailing_costs.id');
        else
            $builder->whereNull('car_detailing_costs.id');
    }



    /**
     * НАЛИЧИЕ СПИСАНИЯ
     */
    public function hasOff(Builder $builder, bool $value)
    {
        if($value)
            $builder->whereNotNull('car_owners.id');
        else
            $builder->whereNull('car_owners.id');
    }



    /**
     * НАЛИЧИЕ ЗАЛОГА
     */
    public function hasDeposit(Builder $builder, bool $value)
    {
        if($value)
            $builder->whereNotNull('car_collectors.id');
        else
            $builder->whereNull('car_collectors.id');
    }



    /**
     * НАЛИЧИЕ СКИДКИ
     */
    public function hasDiscount(Builder $builder, bool $value)
    {
        if($value)
            $builder->whereNotNull('discounts.id');
        else
            $builder->whereNull('discounts.id');
    }



    /**
     * НАЛИЧИЕ ПЕРЕОЦЕНКИ
     */
    public function hasOverPrice(Builder $builder, bool $value)
    {
        if($value)
            $builder->where('overprice.price', '>', 0);
        else
            $builder->where(function($query) {
                $query->whereNull('overprice.price')
                    ->orWhere('overprice.price', 0);
            });
    }



    /**
     * НАЛИЧИЕ ТЮНИНГА
     */
    public function hasTuning(Builder $builder, bool $value)
    {
        if($value)
            $builder->whereNotNull('tuning.price');
        else
            $builder->whereNull('tuning.price');
    }



    /**
     * НАЛИЧИЕ ПОДАРКА
     */
    public function hasGift(Builder $builder, bool $value)
    {
        if($value)
            $builder->whereNotNull('gift.price');
        else
            $builder->whereNull('gift.price');
    }



    /**
     * ИНТЕРВАЛ ЦЕНЫ
     */
    public function prices(Builder $builder, array $arrPrices)
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
     * Держатель залога
     */
    public function collector(Builder $builder, array $arrCollector)
    {
        $builder->whereIn('car_collectors.collector_id', $arrCollector);
    }



    /**
     * НАЧАЛО ПЛАТНОГО ПЕРИОДА
     */
    public function paidDate(Builder $builder, array $paidDate)
    {
        $date_1 = Carbon::createFromFormat('d.m.Y', $paidDate[0])->format('Y-m-d');
        $date_2 = isset($paidDate[1]) ? Carbon::createFromFormat('d.m.Y', $paidDate[1])->format('Y-m-d') : $date_1;
        $builder->whereBetween('car_paid_dates.date_at', [$date_1, $date_2]);
    }



    /**
     * КОНТРОЛЬ СРОКА ОПЛАТЫ
     */
    public function controllPaidDate(Builder $builder, array $paidDate)
    {
        $date_1 = Carbon::createFromFormat('d.m.Y', $paidDate[0])->format('Y-m-d');
        $date_2 = isset($paidDate[1]) ? Carbon::createFromFormat('d.m.Y', $paidDate[1])->format('Y-m-d') : $date_1;
        $builder->whereBetween('car_controll_paid_dates.date_at', [$date_1, $date_2]);
    }



    /**
     * УСЛОВИЯ ПОСТАВКИ
     */
    public function deliveryTerm(Builder $builder, array $arrTerm)
    {
        $builder->whereIn('car_delivery_terms.delivery_term_id', $arrTerm);
    }



    /**
     * ПРИНИМАЮЩИЙ ТЕХНИК
     */
    public function technic(Builder $builder, array $arrTechnic)
    {
        $builder->whereIn('car_technics.technic_id', $arrTechnic);
    }



    /**
     * ВСЕ ЛОГИСТИЧЕСКИЕ ДАТЫ
     */
    public function logisticDates(Builder $builder, array $data)
    {
        if(!count($data))
            return;
        
        $builder->where(function($dateQuery) use($data){
            foreach($data as $key => $dateInterval)
                $dateQuery->orWhere(function($builderOrderDate) use ($key, $dateInterval){
                    $date_1 = Carbon::createFromFormat('d.m.Y', $dateInterval[0])->format('Y-m-d');
                    $date_2 = isset($dateInterval[1]) ? Carbon::createFromFormat('d.m.Y', $dateInterval[1])->format('Y-m-d') : $date_1;
                    $builderOrderDate->where('car_date_logistics.logistic_system_name', $key)
                        ->whereBetween('car_date_logistics.date_at', [$date_1, $date_2]);
                });
        });
    }



    /**
     * Тип статуса автомобиля (свободный, резерв, клиент, Продан)
     */
    public function typeStatus(Builder $builder, array $value)
    {
        $arr = array_unique($value);

        $result = array_intersect($arr, CarStatusType::VALUES);

        $builder->whereIn('car_status_types.status', $result);
    }



    /**
     * Выбранные ИД
     */
    public function ids(Builder $builder, array $value)
    {
        $builder->whereIn('cars.id', $value);
    }



    /**
     * Поиск по Вин, ИД, НомерЗаказа
     */
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
            case 'model':
                $builder->where('marks.name', 'LIKE', '%' . $val . '%');
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



    /**
     * Логистический статус (В заказе, в отгрузке и тд)
     */
    public function stateStatus(Builder $builder, array $value)
    {
        $baseState = CarState::pluck('status')->toArray();

        $arr = array_intersect($baseState, $value);

        $builder->whereIn('cars.status', $arr);
    }



    /**
     * НомерЗаказа
     */
    public function orderNumber(Builder $builder, $value)
    {
        $builder->where('car_orders.order_number', 'LIKE', '%' . $value . '%');
    }



    /**
     * Товарный признак
     */
    public function tradeMarkerId(Builder $builder, array|string $value)
    {
        if(is_string($value))
            $builder->where('car_trade_markers.trade_marker_id', $value);
        elseif(is_array($value))
            $builder->whereIn('car_trade_markers.trade_marker_id', $value);
    }



    /**
     * КонтрМарка
     */
    public function markerId(Builder $builder, array|string $value)
    {
        if(is_string($value))
            $builder->where('car_markers.marker_id', $value);
        elseif(is_array($value))
            $builder->whereIn('car_markers.marker_id', $value);
    }



    /**
     * Бренд
     */
    public function brandIds(Builder $builder, array $value)
    {
        $builder->whereIn('cars.brand_id',  $value);
    }



    /**
     * Модель
     */
    public function markIds(Builder $builder, array $value)
    {
        $builder->whereIn('cars.mark_id',  $value);
    }



    /**
     * Год выпуска
     */
    public function year(Builder $builder, $value)
    {
        $builder->where('cars.year', $value);
    }



    /**
     * ВИН
     */
    public function vin(Builder $builder, $value)
    {
        $builder->where('cars.vin', 'LIKE', '%' . $value . '%');
    }



    /**
     * Код комплектации
     */
    public function complectationCode(Builder $builder, $value)
    {
        $builder->where('complectations.code', $value);
    }



    /**
     * ИД Комплектации
     */
    public function complectationId(Builder $builder, $value)
    {
        $builder->where('complectations.id', $value);
    }



    /**
     * Тип кузова
     */
    public function bodyWorks(Builder $builder, $value)
    {
        $builder->whereIn('complectations.body_work_id', $value);
    }



    /**
     * Трансмиссия
     */
    public function transmission(Builder $builder, array $value)
    {
        $builder->whereIn('motors.motor_transmission_id', $value);
    }



    /**
     * Привод
     */
    public function drivers(Builder $builder, array $value)
    {
        $builder->whereIn('motors.motor_driver_id', $value);
    }
}
