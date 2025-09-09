<?php

namespace App\Http\Filters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * @OA\Schema(
 *   description = "Параметры фильтрации аудитов"
 * )
 */
Class AuditMasterFilter extends AbstractFilter
{
    /**
     * //TODO Доделать фильтры
     * Сделаны те что в ПДФ (
     * ids, 
     * authors, 
     * audits,
     * managers,
     * salons,
     * structures,
     * statuses,
     * create_interval,
     * )
     */

    public const INIT = 'init';

    /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы мастер-аудитов", 
     * property="ids", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const IDS = 'ids';

    /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы авторов/аудиторов.", 
     * property="authors", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const AUTHORS = 'authors';

    /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы чек-листа.", 
     * property="audits", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const AUDITS = 'audits';

    /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы менеджеров.", 
     * property="managers", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const MANAGERS = 'managers';

    /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы салонов.", 
     * property="salons", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const SALONS = 'salons';

    /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы подразделения.", 
     * property="structures", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const STRUCTURES = 'structures';

    /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы статусов.", 
     * property="statuses", 
     * type="array", 
     * example="['wait', 'arbitr', 'close']", 
     * @OA\Items())
     * */
    public const STATUSES = 'statuses';

    /**  @OA\Property(
     * format="string", 
     * description="Массив содержащий название интервала создания мастера-аудита (month,week,today,yesterday).", 
     * property="create_interval", 
     * type="string", 
     * example="today"
     * )
     * */
    public const CREATE_INTERVAL = 'create_interval';



     /**  @OA\Property(
     *      format="array", 
     *      description="Массив содержащий интервал даты создания от - до, параметр ДО необязателен, 
     *      отсутствие второго параметра, будет означать, что используется не интервал, 
     *      соответственно сравнение будет строго по одному параметру", 
     *      property="create_date", 
     *      type="array", 
     *      example="[01.10.2024,22.10.2024]", 
     *      @OA\Items()
     * )
     * */
    public const CREATE_DATE = 'create_date';



         /**  @OA\Property(
     *      format="array", 
     *      description="Массив содержащий интервал даты изменения от - до, параметр ДО необязателен, 
     *      отсутствие второго параметра, будет означать, что используется не интервал, 
     *      соответственно сравнение будет строго по одному параметру", 
     *      property="update_date", 
     *      type="array", 
     *      example="[01.10.2024,22.10.2024]", 
     *      @OA\Items()
     * )
     * */
    public const UPDATE_DATE = 'update_date';

    /**  @OA\Property(
     * format="bool", 
     * description="Удаленные, 1 - да, 0 нет.", 
     * property="trashed", 
     * type="bool", 
     * example="1")
     * */
    public const TRASHED = 'trashed';

    /**  @OA\Property(
     * format="bool", 
     * description="Наличие успешные, 1 - да, 0 нет.", 
     * property="completed", 
     * type="bool", 
     * example="1")
     * */
    public const COMPLETED = 'completed';

    /**  @OA\Property(
     * format="bool", 
     * description="Только мои, 1 - да, 0 нет.", 
     * property="my", 
     * type="bool", 
     * example="1")
     * */
    public const MY = 'my';

    /**  @OA\Property(
     * format="array", 
     * description="Массив содержащий идентификаторы целей обращения.", 
     * property="appeals", 
     * type="array", 
     * example="[1,2]", 
     * @OA\Items())
     * */
    public const APPEALS = 'appeals';



    public function __construct(array $queryParams)
    {
        $queryParams['init'] = 'init';

        parent::__construct($queryParams);
    }



    public function getCallbacks() : array
    {
        return [
            self::INIT              => [$this, 'init'],
            
            self::IDS               => [$this, 'fnIds'],
            self::AUTHORS           => [$this, 'fnAuthors'],
            self::AUDITS            => [$this, 'fnAudits'],
            self::MANAGERS          => [$this, 'fnManagers'],
            self::SALONS            => [$this, 'fnSalons'],
            self::STRUCTURES        => [$this, 'fnStructures'],
            self::STATUSES          => [$this, 'fnStatuses'],
            self::CREATE_INTERVAL   => [$this, 'fnCreateInterval'],
            self::CREATE_DATE       => [$this, 'fnCreateDate'],
            self::UPDATE_DATE       => [$this, 'fnUpdateDate'],
            self::TRASHED           => [$this, 'fnTrashed'],
            self::COMPLETED         => [$this, 'fnCompleted'],
            self::MY                => [$this, 'fnMy'],
            self::APPEALS           => [$this, 'fnAppeals'],
        ];
    }



    public function init(Builder $builder,)
    {
        $builder
            ->leftJoin('trafics', 'trafics.id', 'audit_masters.trafic_id')
            ->leftJoin('trafic_appeals', 'trafic_appeals.id', 'trafics.trafic_appeal_id')
            ->leftJoin('audits', 'audits.id', 'audit_masters.audit_id')
            ->leftJoin('companies', 'companies.id', 'trafics.company_id')
            ->leftJoin('company_structures', 'company_structures.id', 'trafics.company_structure_id');
    }



    public function fnAppeals(Builder $builder, array $data)
    {
        $builder->whereIn('trafic_appeals.appeal_id', $data);
    }



    public function fnMy(Builder $builder, bool $val)
    {
        $userId = auth()->user()->id;

        match($val){
            true => $builder->where(function($q) use($userId){
                        $q->where('audit_masters.author_id', $userId)
                            ->orWhere('trafics.manager_id', $userId);
                    }),
            false => $builder->where(function($q) use($userId){
                        $q->where('audit_masters.author_id', '<>', $userId)
                            ->orWhere('trafics.manager_id', '<>', $userId);
                    }),
            default => '',
        };        
    }



    public function fnTrashed(Builder $builder, bool $val)
    {
        match($val){
            true => $builder->whereNotNull('audit_masters.deleted_at'),
            false => $builder->whereNull('audit_masters.deleted_at'),
            default => '',
        };
    }



    public function fnCompleted(Builder $builder, bool $val)
    {
        match($val){
            true => $builder->whereRaw('audits.complete < audit_masters.point'),
            false => $builder->whereRaw('audits.complete > audit_masters.point'),
            default => ''
        };
    }



    public function fnCreateDate(Builder $builder, array $date)
    {
        $date_1 = Carbon::createFromFormat('d.m.Y', $date[0])->format('Y-m-d');
        $date_2 = isset($date[1]) ? Carbon::createFromFormat('d.m.Y', $date[1])->format('Y-m-d') : $date_1;
        $builder->whereBetween('audit_masters.created_at', [$date_1, $date_2]);
    }



    public function fnUpdateDate(Builder $builder, array $date)
    {
        $date_1 = Carbon::createFromFormat('d.m.Y', $date[0])->format('Y-m-d');
        $date_2 = isset($date[1]) ? Carbon::createFromFormat('d.m.Y', $date[1])->format('Y-m-d') : $date_1;
        $builder->whereBetween('audit_masters.updated_at', [$date_1, $date_2]);
    }



    public function fnCreateInterval(Builder $builder, string $value)
    {
        $now = now();
        
        match($value){
            'month' => $builder->where(function($q) use($now){
                $q->whereYear('audit_masters.created_at', '=', $now->year)
                    ->whereMonth('audit_masters.created_at', '=', $now->month);
            }),
            'week' => $builder->whereBetween('audit_masters.created_at', [
                    $now->startOfWeek(), $now->endOfWeek()
                ]),
            'today' => $builder->whereDate('audit_masters.created_at', $now),
            'yesterday' => $builder->whereDate('audit_masters.created_at', $now),
            default => '',
        };
    }



    public function fnStatuses(Builder $builder, array $val)
    {
        $builder->whereIn('audit_masters.status', $val);
    }



    public function fnStructures(Builder $builder, array $val)
    {
        $builder->whereIn('company_structures.structure_id', $val);
    }



    public function salons(Builder $builder, array $val)
    {
        $builder->whereIn('companies.id', $val);
    }



    public function fnIds(Builder $builder, array $arr)
    {
        $builder->whereIn('audit_masters.id', $arr);
    }



    public function fnAuthors(Builder $builder, array $arr)
    {
        $builder->whereIn('audit_masters.author_id', $arr);
    }



    public function fnAudits(Builder $builder, array $arr)
    {
        $builder->whereIn('audits.id', $arr);
    }



    public function fnManagers(Builder $builder, array $arr)
    {
        $builder->whereIn('trafics.manager_id', $arr);
    }
}