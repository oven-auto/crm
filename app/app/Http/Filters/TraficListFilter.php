<?php

namespace App\Http\Filters;

use App\Http\Filters\AbstractFilter;
use Illuminate\Database\Eloquent\Builder;

Class TraficListFilter extends AbstractFilter{

    public const SHOW = 'show';
    public const CONTROL_DATE = 'control_date';
    public const MANAGER_ID = 'manager_id';
    public const DATE_FOR_CLOSING = 'date_for_closing';
    public const OPENING = [1,2,6];
    public const CLOSING = [3,4,5];

    public const INIT = 'init';


    
    public function __construct(array $queryParams)
    {
        $queryParams['init'] = 'init';
        parent::__construct($queryParams);
    }



    protected function getCallbacks(): array
    {
        return [

            self::SHOW              => [$this, 'show'],
            self::CONTROL_DATE      => [$this, 'controlDate'],
            self::MANAGER_ID        => [$this, 'managerId'],
            self::DATE_FOR_CLOSING  => [$this, 'dateForClosing'],
            self::INIT              => [$this, 'init'],

        ];
    }



    public function init(Builder $builder)
    {
        $builder->leftJoin('trafic_controls', 'trafic_controls.trafic_id', 'trafics.id');
        $builder->leftJoin('trafic_appeals', 'trafic_appeals.id', 'trafics.trafic_appeal_id');

        if(!$this->getQueryParam('date_for_closing'))
        {
            //Получить ожидающие которые может обработать пользователь
            //добавляем таблицу ссылок цели обращения
            
            $builder->OrWhere(function($q){
                //только ожидающие
                $q->where('trafics.trafic_status_id',1);
                
                if(auth()->user())
                {
                    //цель должна быть у пользователя
                    $q->whereIn('trafic_appeals.appeal_id', auth()->user()->appeals->pluck('id'));
                    //структура трафика равна структуре цели обращения
                    $q->whereIn('trafics.company_structure_id', auth()->user()->structures->pluck('company_structure_id'));
                }
                else
                {
                    $user = \App\Models\User::find($this->getQueryParam('manager_id'));
                    //цель должна быть у пользователя
                    $q->whereIn('trafic_appeals.appeal_id', $user->appeals->pluck('id'));
                    //структура трафика равна структуре цели обращения
                    $q->whereIn('trafics.company_structure_id', $user->structures->pluck('company_structure_id'));
                }
            });
        }
    }



    public function show(Builder $builder, $value)
    {
        switch ($value) {
            case 'closing':
                $builder->whereIn('trafics.trafic_status_id', self::CLOSING);
                break;
            case 'opening':
                $builder->whereIn('trafics.trafic_status_id', self::OPENING);
                break;
            default:
                $builder->whereIn('trafics.trafic_status_id', self::OPENING);
        }
    }



    public function controlDate(Builder $builder, $value)
    {
        $date = $this->formatDate($value);

        if(now()->format('Y-m-d') >= $date)
            $builder->where(function($query) use ($date){
                $query->whereDate('trafic_controls.begin_at', '<=', $date)->orWhereNull('trafic_controls.begin_at');
            });
        else
            $builder->whereDate('trafic_controls.begin_at', '=', $date);
    }



    public function managerId(Builder $builder, $value)
    {
        if(is_array($value))
            $builder->where(function($query) use ($value) {
                $query
                    ->whereIn('trafics.manager_id', $value)
                    ->orWhereIn('trafics.author_id', $value);
            });
        else
            $builder->where(function($query) use ($value){
                $query->where('trafics.manager_id', $value)
                    ->orWhere('trafics.author_id', $value);
            });
    }



    public function dateForClosing(Builder $builder, $value)
    {
        $date = $this->formatDate($value);

        $builder->whereDate('trafic_controls.begin_at', '=', $date);
    }
}
