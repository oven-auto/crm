<?php

namespace App\Http\Filters;

use App\Helpers\Date\DateHelper;
use App\Models\Client;
use App\Models\Worksheet\Service\WSMServiceCar;
use Illuminate\Database\Eloquent\Builder;

class CreditFilter extends AbstractFilter
{
    private const KEY = 'wsm_credits.id';
    
    public const INIT               = 'init';
    public const IDS                = 'ids'; //ids
    public const SORT               = 'sort'; //сортировка
    public const SEARCH             = 'search'; //поиск
    public const WORKSHEET          = 'worksheet_id'; //id РЛ
    public const TACTIC             = 'tactic'; //тактика
    public const IS_BROKER          = 'is_broker'; //брокерская сделка
    public const CREDITOR           = 'creditor'; //банк/кредитор
    public const HASREGISTER        = 'has_register'; //Оформление да\нет
    public const REGISTRATION       = 'registration'; //Оформление период
    public const STATUS             = 'status'; //Статус заявки
    public const STATE              = 'state'; //состоияние кредита
    public const HAS_CLOSE          = 'has_close'; //Расторжение да/нет
    public const HAS_AWARD          = 'has_award'; // Начисление
    public const DECORATOR          = 'decorator'; //Оформитель
    public const AUTHOR             = 'author'; // Автор
    public const CAR_TYPE           = 'car_type';
    public const IS_ACTUAL          = 'is_actual';



    protected function getCallbacks(): array
    {
        return [
            self::INIT          => [$this, 'init'],
            self::IDS           => [$this, 'ids'],
            self::SORT          => [$this, 'sort'],
            self::SEARCH        => [$this, 'search'],
            self::WORKSHEET     => [$this, 'worksheet'],
            self::TACTIC        => [$this, 'fnTactic'],
            self::IS_BROKER     => [$this, 'fnBroker'],
            self::CREDITOR      => [$this, 'fnCreditor'],
            self::HASREGISTER   => [$this, 'fnHasRegister'],
            self::REGISTRATION  => [$this, 'fnRegistration'],
            self::STATUS        => [$this, 'fnStatus'],
            self::STATE         => [$this, 'fnState'],
            self::HAS_CLOSE     => [$this, 'fnHasClose'],
            self::HAS_AWARD     => [$this, 'fnHasAward'],
            self::AUTHOR        => [$this, 'fnAuthor'],
            self::DECORATOR     => [$this, 'fnDecorator'],
            self::CAR_TYPE      => [$this, 'car_type'],
            self::IS_ACTUAL     => [$this, 'isActual'],
        ];
    }



    public function __construct(array $queryParams)
    {
        $queryParams['sort'] = $queryParams['sort'] ?? '';

        $queryParams['init'] = 'init';

        parent::__construct($queryParams);
    }



    public function init(Builder $builder)
    {
        $builder
            ->leftJoin('worksheets', 'worksheets.id', 'wsm_credits.worksheet_id')
            ->leftJoin('clients', 'clients.id', 'wsm_credits.debtor_id')
            ->leftJoin('client_phones', 'client_phones.client_id', 'clients.id')
            ->leftJoin('wsm_credit_awards', 'wsm_credit_awards.wsm_credit_id', self::KEY)
            ->leftJoin('wsm_credit_calculations', 'wsm_credit_calculations.wsm_credit_id', self::KEY)
            ->leftJoin('wsm_credit_cars', 'wsm_credit_cars.wsm_credit_id', self::KEY)
            ->leftJoin('wsm_credit_contracts', 'wsm_credit_contracts.wsm_credit_id', self::KEY)
            ->leftJoin('wsm_credit_deductions', 'wsm_credit_deductions.wsm_credit_id', self::KEY)
            ->leftJoin('wsm_credit_services', 'wsm_credit_services.wsm_credit_id', self::KEY)
            ->leftJoin('wsm_services', 'wsm_services.id', 'wsm_credit_services.wsm_service_id')
            ->leftJoin('wsm_credit_states', 'wsm_credit_states.wsm_credit_id', self::KEY);     
            
        $builder->groupBy(self::KEY);
    }



    public function isActual(Builder $builder, bool $val)
    {
        match($val) {
            true => $builder->whereRaw('wsm_credit_cars.vin in (
                SELECT c.vin FROM wsm_reserve_new_cars wrnc
                LEFT JOIN cars c on c.id = wrnc.car_id
                WHERE wrnc.deleted_at is NULL AND wrnc.worksheet_id = worksheets.id
            )'),
            false => $builder->whereRaw('wsm_credit_cars.vin not in (
                SELECT c.vin FROM wsm_reserve_new_cars wrnc
                LEFT JOIN cars c on c.id = wrnc.car_id
                WHERE wrnc.deleted_at is NULL AND wrnc.worksheet_id = worksheets.id
            )'),
            default => ''
        };
    }



    public function car_type(Builder $builder, array $value)
    {
        $searchType = [];

        foreach($value as $item)
            $searchType[] = WSMServiceCar::getModelName($item);

        if(count($searchType))
            $builder->whereIn('wsm_credit_cars.carable_type', $searchType);
    }



    public function fnDecorator(Builder $builder, array $data)
    {
        $builder->whereIn('wsm_credit_contracts.decorator_id', $data);
    }



    public function fnAuthor(Builder $builder, array $data)
    {
        $builder->whereIn('wsm_credits.author_id', $data);
    }



    public function fnHasAward(Builder $builder, bool $val)
    {
        match($val){
            true => $builder->where('wsm_credit_awards.completed', 1),
            false => $builder->where('wsm_credit_awards.completed', 0),
            default => '',
        };
    }



    public function fnHasClose(Builder $builder, bool $val)
    {
        match($val){
            true => $builder->where('wsm_credits.close', 1),
            false => $builder->where('wsm_credits.close', '<>', 1),
            default => '',
        };
    }



    public function fnState(Builder $builder, array $data)
    {
        $builder->whereIn('wsm_credit_states.state', $data);
    }



    public function fnStatus(Builder $builder, array $data)
    {
        $builder->whereIn('wsm_credits.status_id', $data);
    }



    public function fnRegistration(Builder $builder, array $date)
    {
        $dates = DateHelper::setDateToCarbon($date, 'd.m.Y');

        $builder->whereBetween('wsm_credit_contracts.register_at', $dates);
    }



    public function fnHasRegister(Builder $builder, bool $val)
    {
        match($val){
            true => $builder->whereNotNull('wsm_credit_contracts.register_at'),
            false => $builder->whereNull('wsm_credit_contracts.register_at'),
            default => '',
        };
    }



    public function fnCreditor(Builder $builder, array $val)
    {
        $builder->whereIn('wsm_credits.creditor_id', $val);
    }



    public function fnBroker(Builder $builder, bool $val)
    {
        match($val){
            true => $builder->where('wsm_credits.broker_deal', true),
            false => $builder->where('wsm_credits.broker_deal', false),
            default => '',
        };        
    }



    public function fnTactic(Builder $builder, array $data)
    {
        $builder->whereIn('wsm_credits.calculation_type', $data);
    }



    public function worksheet(Builder $builder, int $value)
    {
        $builder->where('wsm_credits.worksheet_id', $value);
    }



    public function ids(Builder $builder, array $val)
    {
        $builder->whereIn('wsm_credits.id', $val);
    }



    public function sort(Builder $builder, string $val)
    {
        $data = match($val){
            'calculate_asc'     => ['wsm_credits.created_at',           'ASC'],
            'calculate_desc'    => ['wsm_credits.created_at',           'DESC'],
            'register_asc'      => ['wsm_credit_contracts.register_at', 'ASC'],
            'register_desc'     => ['wsm_credit_contracts.register_at', 'DESC'],
            default             => ['wsm_credits.id',                   'DESC']
        };

        $builder->orderBy($data[0], $data[1]);
    }



    public function search(Builder $builder, string $value)
    {   
        $builder->where(function($subQ) use ($value){
            $subQ->where('clients.lastname', 'LIKE', '%'.$value.'%');
            $subQ->orWhere('clients.company_name', 'LIKE', '%'.$value.'%');
            $subQ->orWhere('client_phones.phone', 'LIKE', '%'.$value.'%');
            $subQ->orWhere('wsm_credit_cars.vin',  'LIKE', '%'.$value.'%');
        });
    }
}