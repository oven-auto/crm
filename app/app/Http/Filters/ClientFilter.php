<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Sanctum\PersonalAccessToken;

class ClientFilter extends AbstractFilter
{
    public const LASTNAME 			= 'lastname';
    public const FIRSTNAME 			= 'firstname';
    public const FATHERNAME 		= 'fathername';
    public const PHONE 				= 'phone';
    public const EMAIL 				= 'email';
    public const CLIENT_TYPE_ID     = 'client_type_id';
    public const TRAFIC_SEX_ID      = 'trafic_sex_id';
    public const TRAFIC_ZONE_ID     = 'trafic_zone_id';
    public const HAS_WORKSHEET      = 'has_worksheet';
    public const INPUT              = 'input';
    public const REGISTER_INTERVAL  = 'register_interval';
    public const REGISTER_START     = 'register_start';
    public const REGISTER_END       = 'register_end';
    public const ACTION_INTERVAL    = 'action_interval';
    public const ACTION_START       = 'action_start';
    public const ACTION_END         = 'action_end';
    public const IDS                = 'ids';
    public const PERSONAL    		= 'personal';
    public const INIT               = 'init';

    protected function getCallbacks(): array
    {
        return [
            self::LASTNAME                  => [$this, 'lastname'],
            self::FIRSTNAME                 => [$this, 'firstname'],
            self::FATHERNAME                => [$this, 'fathername'],
            self::PHONE                     => [$this, 'phone'],
            self::EMAIL                     => [$this, 'email'],
            self::CLIENT_TYPE_ID            => [$this, 'clientTypeId'],
            self::TRAFIC_SEX_ID             => [$this, 'traficSexId'],
            self::TRAFIC_ZONE_ID            => [$this, 'traficZoneId'],
            self::HAS_WORKSHEET             => [$this, 'hasWorksheet'],
            self::INPUT                     => [$this, 'input'],
            self::REGISTER_INTERVAL         => [$this, 'registerInterval'],
            self::REGISTER_START            => [$this, 'registerStart'],
            self::REGISTER_END              => [$this, 'registerEnd'],
            self::ACTION_INTERVAL           => [$this, 'actionInterval'],
            self::ACTION_START              => [$this, 'actionStart'],
            self::ACTION_END                => [$this, 'actionEnd'],
            self::IDS                       => [$this, 'ids'],
            self::INIT                      => [$this, 'init'],
        ];
    }
    
    
    
    public function __construct(array $queryParams)
    {
        $queryParams['init'] = 'init';
        parent::__construct($queryParams);
    }
    
    
    
    public function init(Builder $builder)
    {
        $builder->leftJoin('client_phones', 'client_phones.client_id','clients.id');
        $builder->leftJoin('client_emails', 'client_emails.client_id','clients.id');
        $builder->leftJoin('client_inns', 'client_inns.client_id','clients.id');
        $builder->leftJoin('worksheets','worksheets.client_id','clients.id');
    }
    


    public function ids(Builder $builder, string|array $value)
    {
        if(is_array($value))
            $builder->whereIn('clients.id', $value);
        elseif(is_string($value))
            $builder->whereIn('clients.id', explode(',',$value));
    }



    public function lastname(Builder $builder, string $value)
    {
        $builder->where('clients.lastname', 'like', '%'. $value.'%');
    }



    public function firstname(Builder $builder, string $value)
    {
        $builder->where('clients.firstname', 'like', '%'. $value.'%');
    }



    public function fathername(Builder $builder, string $value)
    {
        $builder->where('clients.fathername', 'like', '%'. $value.'%');
    }



    public function phone(Builder $builder, string $value)
    {
        $value = preg_replace('/[^0-9]/', '', $value);
        $builder->where('client_phones.phone', 'like', '%'. $value.'%');
    }



    public function email(Builder $builder, string $value)
    {
        $builder->where('client_emails.email', 'like', '%'. $value.'%');
    }



    public function clientTypeId(Builder $builder, int $value)
    {
        $builder->where('clients.client_type_id', $value);
    }



    public function traficSexId(Builder $builder, int $value)
    {
        $builder->where('clients.trafic_sex_id', $value);
    }



    public function traficZoneId(Builder $builder, int $value)
    {
        $builder->where('clients.trafic_zone_id', $value);
    }



    public function hasWorksheet(Builder $builder, bool|int $value)
    {
        if($value)
            $builder->whereNotNull('worksheets.id');
        else
            $builder->whereNull('worksheets.id');
    }


    
    public function input(Builder $builder, string $value)
    {
        $builder->where(function($query) use ($value){
            $query->orWhere('clients.lastname', 'like', '%'. $value.'%');
            $query->orWhere('clients.firstname', 'like', '%'. $value.'%');
            $query->orWhere('client_phones.phone', 'like', '%'. $value.'%');
            $query->orWhere('client_emails.email', 'like', '%'. $value.'%');
            $query->orWhere('client_inns.number', 'like', '%'. $value.'%');
            $query->orWhere('clients.id', $value);
            $query->orWhere('clients.company_name', 'like', '%'. $value.'%');
        });
    }

    
    
    public function registerInterval(Builder $builder, string $value)
    {
        $now = now();
        
        match($value){
            'month' => $builder->where(function($q) use($now){
                $q->whereYear('clients.created_at', '=', $now->year)
                    ->whereMonth('clients.created_at', '=', $now->month);
            }),
            'week' => $builder->whereBetween('clients.created_at', [
                    $now->startOfWeek(), $now->endOfWeek()
                ]),
            'today' => $builder->whereDate('clients.created_at', $now),
            'yesterday' => $builder->whereDate('clients.created_at', $now),
            default => '',
        };
    }

    
    
    public function registerStart(Builder $builder, string $value)
    {
        $builder->whereDate('clients.created_at','>=', $this->formatDate($value));
    }

    
    
    public function registerEnd(Builder $builder, string $value)
    {
        $builder->whereDate('clients.created_at','<=', $this->formatDate($value));
    }

    
    
    public function actionInterval(Builder $builder, string $value)
    {
        $now = now();
        
        match($value){
            'month' => $builder->where(function($q)  use($now){
                    $q->whereYear('worksheets.created_at', '=', $now->year)
                        ->whereMonth('worksheets.created_at', '=', $now->month);
                }),
            'week' => $builder->whereBetween('worksheets.created_at', [
                    $now->startOfWeek(), $now->endOfWeek()
                ]),
            'today' => $builder->whereDate('worksheets.created_at', $now),
            'yesterday' => $builder->whereDate('worksheets.created_at', $now->subDay()),
            default => ''
        };
    }
    
    

    public function actionStart(Builder $builder, string $value)
    {
        $builder->whereDate('worksheets.created_at','>=', $this->formatDate($value));
    }
    
    

    public function actionEnd(Builder $builder, string $value)
    {
        $builder->whereDate('worksheets.created_at','<=', $this->formatDate($value));
    }
}