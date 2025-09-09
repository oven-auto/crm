<?php

namespace App\Services\Client\Rating;

use App\Http\Filters\AbstractFilter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class RatingClientFilter extends AbstractFilter
{
    public const CLIENT_TYPE    = 'client_type';
    public const SEX            = 'sex';
    public const REGISTRATION   = 'registration';
    public const MOST_ACTIVE    = 'most_active';

    public const INIT           = 'init';

    public function __construct(array $queryParams)
    {
        $queryParams['init'] = $queryParams;
        parent::__construct($queryParams);
    }



    protected function getCallbacks(): array
    {
        return [
            self::CLIENT_TYPE           => [$this, 'clientType'],
            self::SEX                   => [$this, 'sex'],
            self::REGISTRATION          => [$this, 'registration'],
            self::MOST_ACTIVE           => [$this, 'mostActive'],

            self::INIT                  => [$this, 'init'],
        ];
    }



    public function init(Builder $builder, array $params)
    {
        $builder->leftJoin('worksheets', 'worksheets.client_id', 'clients.id');

        $builder->groupBy('clients.id');
    }



    /**
     * Самые активные пользователи
     */
    public function mostActive(Builder $builder, int $val)
    {
        $builder
            ->addSelect(DB::raw('count(w.id) as w_count'))
            ->orderBy('w_count', 'DESC')
            ->limit($val);
    }



    /**
     * Тип клиента из таблицы client_types физик/юрик/собнужды
     */
    public function clientType(Builder $builder, int $val)
    {
        $builder->where('clients.client_type_id', $val);
    }



    /**
     * Пол клиента из таблицы client_sexes муж/жен
     */
    public function sex(Builder $builder, int $val)
    {
        $builder->where('clients.trafic_sex_id', $val);
    }



    /**
     * По дате регистрации клиента в системе
     */
    public function registration(Builder $builder, array $date)
    {
        $date_1 = Carbon::createFromFormat('d.m.Y', $date[0])->format('Y-m-d');
        $date_2 = isset($date[1]) ? Carbon::createFromFormat('d.m.Y', $date[1])->format('Y-m-d') : $date_1;
        $builder->whereBetween('clients.created_at', [$date_1, $date_2]);
    }
}