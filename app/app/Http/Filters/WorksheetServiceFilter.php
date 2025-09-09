<?php

namespace App\Http\Filters;

use App\Helpers\Date\DateHelper;
use App\Models\Worksheet\Service\WSMServiceCar;
use Illuminate\Database\Eloquent\Builder;

Class WorksheetServiceFilter extends AbstractFilter
{
    private const KEY = 'wsm_services.id';

    public const IDS = 'ids';
    public const INIT = 'init';
    public const SEARCH = 'search';
    public const DECORATOR = 'decorator';
    public const MANAGER = 'manager';
    public const CAR_TYPE = 'car_type';//Применяемость
    public const CATEGORY = 'category';
    public const SERVICE = 'service'; //наименование
    public const SORT = 'sort';
    public const SALE_INTERVAL = 'sale_interval';
    public const WORKSHEET = 'worksheet_id';
    public const HAS_REGISTRATION = 'has_registration';//есть оформление
    public const REGISTRATION = 'registration';// период оформления
    public const BEGIN = 'begin'; //Начало договора
    public const HAS_CLOSE = 'has_close';//Расторжение
    public const HAS_AWARD = 'has_award';//Вознограждение
    public const PROVIDER = 'provider';//Поставщик
    public const STATE  = 'state';
    
    public const HAS_EVENT = 'has_event';
    public const PAYMENT = 'payment';
    public const IN_CREDIT = 'in_credit';
    public const IS_ACTUAL = 'is_actual';

    public function getCallbacks() : array
    {
        return [
            self::INIT                  => [$this, 'init'],
            self::IDS                   => [$this, 'ids'],
            self::SEARCH                => [$this, 'search'],
            self::DECORATOR             => [$this, 'decorator'],
            self::MANAGER               => [$this, 'manager'],
            self::CAR_TYPE              => [$this, 'car_type'],
            self::CATEGORY              => [$this, 'category'],
            self::SERVICE               => [$this, 'service'],
            self::SORT                  => [$this, 'sort'],
            self::SALE_INTERVAL         => [$this, 'sale_interval'],
            self::WORKSHEET             => [$this, 'worksheet'],
            self::HAS_REGISTRATION      => [$this, 'hasRegistration'],
            self::REGISTRATION          => [$this, 'registration'],
            self::BEGIN                 => [$this, 'begin'],
            self::HAS_CLOSE             => [$this, 'hasClose'],
            self::HAS_AWARD             => [$this, 'hasAward'],
            self::PROVIDER              => [$this, 'provider'],
            self::STATE                 => [$this, 'state'],

            self::HAS_EVENT             => [$this, 'hasEvent'],
            self::PAYMENT               => [$this, 'payment'],
            self::IN_CREDIT             => [$this, 'inCredit'],
            self::IS_ACTUAL             => [$this, 'isActual'],
        ];
    }



    public function init(Builder $builder)
    {
        $builder            
            ->leftJoin('wsm_service_awards', 'wsm_service_awards.wsm_service_id', self::KEY)
            ->leftJoin('wsm_service_cars', 'wsm_service_cars.wsm_service_id', self::KEY)
            ->leftJoin('wsm_service_contracts', 'wsm_service_contracts.wsm_service_id', self::KEY)
            ->leftJoin('wsm_service_deductions', 'wsm_service_deductions.wsm_service_id', self::KEY)
            ->leftJoin('services', 'services.id', 'wsm_services.service_id')
            ->leftJoin('service_categories', 'service_categories.id', 'services.category_id')
            ->leftJoin('worksheets', 'worksheets.id', 'wsm_services.worksheet_id')
            ->leftJoin('clients', 'clients.id', 'worksheets.client_id')
            ->leftJoin('client_phones', 'client_phones.client_id', 'clients.id')
            ->leftJoin('wsm_service_states', 'wsm_service_states.wsm_service_id', self::KEY)
            ->leftJoin('wsm_service_client_events', 'wsm_service_client_events.wsm_service_id', self::KEY)
            ->leftJoin('wsm_credit_services', 'wsm_credit_services.wsm_service_id', self::KEY)
            
            ->leftJoin('wsm_reserve_new_cars', 'wsm_reserve_new_cars.worksheet_id', 'worksheets.id')
            ->leftJoin('cars', 'cars.id', 'wsm_reserve_new_cars.car_id');
        
        $builder->groupBy(self::KEY);
    }



    public function isActual(Builder $builder, bool $val)
    {
        match($val) {
            true => $builder->whereRaw('wsm_service_cars.vin in (
                SELECT c.vin FROM wsm_reserve_new_cars wrnc
                LEFT JOIN cars c on c.id = wrnc.car_id
                WHERE wrnc.deleted_at is NULL AND wrnc.worksheet_id = worksheets.id
            )'),
            false => $builder->whereRaw('wsm_service_cars.vin not in (
                SELECT c.vin FROM wsm_reserve_new_cars wrnc
                LEFT JOIN cars c on c.id = wrnc.car_id
                WHERE wrnc.deleted_at is NULL AND wrnc.worksheet_id = worksheets.id
            )'),
            default => ''
        };
    }



    public function inCredit(Builder $builder, bool $val)
    {
        match($val){
            true => $builder->whereNotNull('wsm_credit_services.wsm_credit_id'),
            false => $builder->whereNull('wsm_credit_services.wsm_credit_id'),
            default => '',
        };
    }



    public function payment(Builder $builder, array $val)
    {
        $builder->whereIn('wsm_services.payment_id', $val);
    }



    public function hasEvent(Builder $builder, bool $val)
    {
        match($val){
            true => $builder->whereNotNull('wsm_service_client_events.client_event_id'),
            false => $builder->whereNull('wsm_service_client_events.client_event_id'),
            default => '',
        };
    }



    public function state(Builder $builder, array $val)
    {
        $builder->whereIn('wsm_service_states.state', $val);
    }



    public function provider(Builder $builder, array $val)
    {
        $builder->whereIn('wsm_services.provider_id', $val);
    }



    public function hasAward(Builder $builder, bool $val)
    {
        match($val){
            true => $builder->where('wsm_service_awards.completed', 1),
            false => $builder->where('wsm_service_awards.completed', 0),
            default => '',
        };
    }



    public function hasClose(Builder $builder, bool $val)
    {
        match($val){
            true => $builder->where('wsm_services.close', 1),
            false => $builder->where('wsm_services.close', 0),
            default => '',
        };
    }



    public function begin(Builder $builder, array $date)
    {
        $dates = DateHelper::setDateToCarbon($date, 'd.m.Y');
       
        $builder->whereBetween('wsm_service_contracts.begin_at', [$dates[0]->format('Y-m-d'), $dates[1]->format('Y-m-d')]);
    }



    public function registration(Builder $builder, array $date)
    {
        $dates = DateHelper::setDateToCarbon($date, 'd.m.Y');

        $builder->whereBetween('wsm_service_contracts.register_at', [$dates[0]->format('Y-m-d'), $dates[1]->format('Y-m-d')]);
    }



    public function hasRegistration(Builder $builder, bool $val)
    {
        match($val){
            true => $builder->whereNotNull('wsm_service_contracts.register_at'),
            false => $builder->whereNull('wsm_service_contracts.register_at'),
            default => '',
        };
    }



    public function __construct(array $queryParams)
    {
        $queryParams['sort'] = $queryParams['sort'] ?? '';

        $queryParams['init'] = 'init';

        parent::__construct($queryParams);
    }



    public function worksheet(Builder $builder, int $value)
    {
        $builder->where('wsm_services.worksheet_id', $value);
    }



    public function ids(Builder $builder, array $values)
    {   
        $builder->whereIn('wsm_services.id', $values);
    }



    public function search(Builder $builder, string $value)
    {
        $builder->where(function($subQ) use ($value){
            $subQ->where('clients.lastname', 'LIKE', '%'.$value.'%');
            $subQ->orWhere('clients.company_name', 'LIKE', '%'.$value.'%');
            $subQ->orWhere('client_phones.phone', 'LIKE', '%'.$value.'%');
            $subQ->orWhere('wsm_service_cars.vin', 'LIKE', '%'.$value.'%');
        });
    }



    public function decorator(Builder $builder, array $values)
    {
        $builder->whereIn('wsm_service_contracts.decorator_id', $values);
    }



    public function manager(Builder $builder, array $values)
    {
        $builder->whereIn('wsm_service_contracts.manager_id', $values);
    }



    public function car_type(Builder $builder, array $value)
    {
        $searchType = [];

        foreach($value as $item)
            $searchType[] = WSMServiceCar::getModelName($item);

        if(count($searchType))
            $builder->whereIn('wsm_service_cars.carable_type', $searchType);
    }



    public function category(Builder $builder, array $values)
    {
        $builder->whereIn('services.category_id', $values);
    }



    public function service(Builder $builder, array $values)
    {
        $builder->whereIn('services.id', $values);
    }



    public function sort(Builder $builder, string $value)
    {
        $data = match($value){
            'calculate_asc'     => ['wsm_services.created_at', 'ASC'],
            'calculate_desc'    => ['wsm_services.created_at', 'DESC'],
            'begin_asc'         => ['wsm_service_contracts.begin_at', 'ASC'],
            'begin_desc'        => ['wsm_service_contracts.begin_at', 'DESC'],
            'register_asc'      => ['wsm_service_contracts.register_at', 'ASC'],
            'register_desc'     => ['wsm_service_contracts.register_at', 'DESC'],
            default             => ['wsm_services.id', 'DESC']
        };

        $builder->orderBy($data[0], $data[1]);
    }



    public function sale_interval(Builder $builder, array $values)
    {
        $dates = DateHelper::setDateToCarbon($values, 'd.m.Y');

        $builder->whereBetween('wsm_service_contracts.register_at', $dates);
    }
}