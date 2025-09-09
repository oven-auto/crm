<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Filterable;
use App\Models\Interfaces\CommentInterface;
use App\Services\Comment\Comment;

class Trafic extends Model implements CommentInterface
{
    use HasFactory, Filterable, SoftDeletes;
    
    protected $with = ['author'];

    public const NOTICES = [
        'open'   => 'Трафик открыт',
        'create' => 'Трафик создан',
        'update' => 'Трафик изменен',
        'close' =>  'Трафик упущен',
        'delete' => 'Трафик удален',
        'file_load' => 'Загружены фаилы',
        'file_delete' => 'Удален фаил',
        'audit_load' => 'Загружен аудит',
        'audit_update' => 'Изменен аудит',
        'clone' => 'Трафик удален, тк клиент уже имел открытый рабочий лист с той же целью обращения.'
    ];

    private const CONTROL_OVERDUE = 1;
    private const CONTROL_ACTUAL = 2;
    private const CONTROL_COMMING = 3;

    protected $guarded = [];

    protected $casts = [
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'processing_at' => 'datetime',
        'deleted_at'    => 'datetime',
    ];



    public static function boot()
    {
        parent::boot();

        static::created(function($item){
            Comment::add($item, 'create');
        });

        static::updating(function($item){
            if($item->isDirty())
                if($item->trafic_status_id == 4 && $item->trafic_status_id != $item->getOriginal('trafic_status_id'))
                    Comment::add($item, 'close');
                elseif($item->trafic_status_id != 5)
                    Comment::add($item, 'update');
        });

        static::deleted(function($item){
            Comment::add($item, 'delete');
            $item->trafic_status_id = 5;
            $item->save();
        });
    }



    public function usersIdByTraficAppeal($notMe = 0)
    {
        $query = \App\Models\User::select('users.id')
            ->leftJoin('trafic_user_appeals', 'trafic_user_appeals.user_id', '=', 'users.id')
            ->leftJoin('trafic_appeals', 'trafic_appeals.appeal_id', '=',  'trafic_user_appeals.appeal_id')
            ->leftJoin('trafics', 'trafics.manager_id','users.id')
            ->leftJoin('user_company_structures', 'user_company_structures.user_id', 'users.id')
            ->where('trafic_appeals.id', $this->trafic_appeal_id)
            ->where('user_company_structures.company_structure_id', $this->company_structure_id);

        if($notMe)
            $query->where('users.id', '<>', auth()->user()->id);

        $query->groupBy('users.id');

        $query->toSql();
        
        $users = $query->pluck('id');

        return $users;
    }



    /**
     * Пометить как принятый
     */
    public function process()
    {
        $this->trafic_status_id = 3;
        $this->save();
        $this->touch();
    }



    /**
     * Является ли траифи, трафиком пользователя
     */
    public function isMy()
    {
        $me = auth()->user()->id;
        if($this->author_id == $me || $this->manager_id == $me)
            return 1;
        return 0;
    }



    public function writeComment($arr_message)
    {
        return $this->comment_list()->create($arr_message);
    }



    /**
     * Проверка черновик или нет
     */
    public function isDraft()
    {
        if($this->trafic_status_id == 6)
            return 1;
        return 0;
    }



    public function scopeOnlyTarget($query)
    {
        $query->whereIn('trafic_clients.client_type_id', [1,2]);
        $query->whereIn('trafics.trafic_status_id', [1,2,3,4]);
    }



    public function auditmaster()
    {
        return $this->hasOne(\App\Models\Audit\AuditMaster::class, 'trafic_id', 'id')->with('audit');
    }



    public function links()
    {
        return $this->hasMany(\App\Models\TraficLink::class, 'trafic_id', 'id');
    }



    public function comment_list()
    {
        return $this->hasMany(\App\Models\TraficComment::class, 'trafic_id', 'id');
    }



    public function getPhoneMaskAttribute()
    {
        $phone = '';
        $from = $this->phone;
        if($this->phone)
            $phone = sprintf("+%s (%s) %s-%s-%s",
                substr($from, 0, 1),
                substr($from, 1, 3),
                '***',
                '**',
                '**',
            );
        return $phone;
    }



    public function getFormatedPhoneAttribute()
    {
        $phone = '';
        $from = $this->phone;
        if($this->phone)
            $phone = sprintf("+%s (%s) %s-%s-%s",
                substr($from, 0, 1),
                substr($from, 1, 3),
                substr($from, 4, 3),
                substr($from, 7, 2),
                substr($from, 9)
            );
        return $phone;
    }



    public function getClientNameAttribute()
    {
        if($this->company_name)
            return $this->company_name;
        return trim(join(' ', [$this->lastname ?? '', $this->firstname ?? '', $this->fathername ?? '']));
    }



    public function scopeFullest($query)
    {
        return $query->with([
            'zone','chanel',
            'salon','structure','appeal',
            'manager','author',
            'needs', 'worksheet.client','processing', 'files'
        ]);
    }

    public function scopeLinksCount($query)
    {
        return $query->withCount('links');
    }



    public function scopeFilesCount($query)
    {
        return $query->withCount('files');
    }



    /** */
    public function status()
    {
        return $this->hasOne(\App\Models\TraficStatus::class, 'id', 'trafic_status_id')->withDefault();
    }



    public function saveNeeds()
    {
        return $this->hasMany(\App\Models\TraficNeed::class, 'trafic_id', 'id');
    }



    public function needs()
    {
        return $this->hasManyThrough(
            \App\Models\TraficProduct::class,
            \App\Models\TraficNeed::class,
            'trafic_id',
            'number',
            'id',
            'trafic_product_number'
        );
    }



    // public function sex()
    // {
    //     return $this->hasOne(\App\Models\TraficSex::class, 'id', 'trafic_sex_id')->withDefault();
    // }



    public function zone()
    {
        return $this->hasOne(\App\Models\TraficZone::class, 'id', 'trafic_zone_id')->withDefault();
    }



    public function chanel()
    {
        return $this->hasOne(\App\Models\TraficChanel::class, 'id', 'trafic_chanel_id')->withDefault();
    }



    public function salon()
    {
        return $this->hasOne(\App\Models\Company::class, 'id', 'company_id')->withDefault();
    }



    public function structure()
    {
        return $this->hasOneThrough(
            \App\Models\Structure::class,
            \App\Models\CompanyStructure::class,
            'id',
            'id',
            'company_structure_id',
            'structure_id'
        );
    }



    public function appeal()
    {
        return $this->hasOneThrough(
            \App\Models\Appeal::class,
            \App\Models\TraficAppeal::class,
            'id',
            'id',
            'trafic_appeal_id',
            'appeal_id'
        );
    }



    public function manager()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'manager_id')->withDefault()->withTrashed();
    }



    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id' , 'author_id')->withDefault()->withTrashed();
    }



    public function worksheet()
    {
        return $this->hasOne(\App\Models\Worksheet::class, 'trafic_id','id')->withDefault();
    }



    public function processing()
    {
        return $this->hasMany(\App\Models\TraficProcessing::class, 'trafic_id', 'id')->with('standart');
    }



    public function files()
    {
        return $this->hasMany(\App\Models\TraficFile::class, 'trafic_id', 'id');
    }

    

    // public function person()
    // {
    //     return $this->hasOne(\App\Models\ClientType::class, 'id', 'client_type_id')->withDefault();
    // }



    /**
     * ОДИН К ОДНОМУ СООБЩЕНИЕ ТРАФИКА
     */
    public function message()
    {
        return $this->hasOne(\App\Models\TraficMessage::class)->withDefault();
    }



    /**
     * ОДИН К ОДНОМУ КОНТРОЛЬНЫЕ ДАТЫ ТРАФИКА
     */
    public function control()
    {
        return $this->hasOne(\App\Models\TraficControl::class)->withDefault();
    }



    /**
     * ОДИН К ОДНОМУ КЛИЕНТ ТРАФИКА
     */
    public function client()
    {
        return $this->hasOne(\App\Models\TraficClient::class)->withDefault();
    }



    /**
     * ATTRIBUTES
     */



    /**
     * Получить сообщение
     */
    public function getCommentAttribute()
    {
        return $this->message->message;
    }



    /**
     * Получить Имя Клиента
     */
    public function getFirstnameAttribute()
    {
        return $this->client->firstname;
    }



     /**
     * Получить Фамилию Клиента
     */
    public function getLastnameAttribute()
    {
        return $this->client->lastname;
    }



     /**
     * Получить отчество Клиента
     */
    public function getFathernameAttribute()
    {
        return $this->client->fathername;
    }



    /**
     * Получить телефон Клиента
     */
    public function getPhoneAttribute()
    {
        return $this->client->phone;
    }



    /**
     * Получить email Клиента
     */
    public function getEmailAttribute()
    {
        return $this->client->email;
    }



    /**
     * Получить ИНН Клиента
     */
    public function getInnAttribute()
    {
        return $this->client->inn;
    }



    /**
     * Получить название компании Клиента
     */
    public function getCompanyNameAttribute()
    {
        return $this->client->company_name;
    }



    /**
     * Получить тип клиента Клиента
     */
    public function getClientTypeIdAttribute()
    {
        return $this->client->client_type_id;
    }



    /**
     * Получить пол Клиента
     */
    public function getTraficSexIdAttribute()
    {
        return $this->client->trafic_sex_id;
    }



    /**
     * Получить конец контроля
     */
    public function getBeginAtAttribute()
    {
        return $this->control->begin_at;
    }



    /**
     * Получить начало контроля
     */
    public function getEndAtAttribute()
    {
        return $this->control->end_at;
    }



    /**
     * Получить интервал контрольных дат
     */
    public function getIntervalAttribute()
    {
        return $this->control->interval;
    }



    /**
     * Get Person model
     */
    public function getPersonAttribute()
    {
        return $this->client->person;
    }



    /**
     * Get SEX model
     */
    public function getSexAttribute()
    {
        return $this->client->sex;
    }



    /**
     * METHODS
     */


    //Ожидающий
    public function isWaiting()
    {
        return $this->trafic_status_id == 1 ? 1 : 0;
    }



    //Назначенный
    public function isPersonal()
    {
        return $this->trafic_status_id == 2 ? 1 : 0;
    }



    public function isWorking()
    {
        return $this->isWaiting() && $this->isPersonal();
    }



    //Принятый
    public function isAccepted()
    {
        return $this->trafic_status_id == 3 ? 1 : 0;
    }


    public function getControlStatus()
    {
        $currentTime = date('Y-m-d H:i');
        if($currentTime > $this->end_at)
            return self::CONTROL_OVERDUE;
        if($currentTime >= $this->begin_at && $currentTime <= $this->end_at)
            return self::CONTROL_ACTUAL;
        if($currentTime < $this->begin_at)
            return self::CONTROL_COMMING;
    }

    public function beginDate()
    {
        $status = $this->getControlStatus();
        switch ($status){
            case 1:
                return $this->begin_at->format('d.m.Y (H:i)').'-'.$this->end_at->format('d.m.Y (H:i)');
            case 2:
                return $this->begin_at->format('d.m.Y (H:i)').'-'.$this->end_at->format('d.m.Y (H:i)');
            case 3:
                $diff = $this->begin_at->diff(now());
                $arr = [
                    $diff->h.'ч.',
                    $diff->i.'мин.',
                ];
                return 'Начало через '.join(' ',$arr);
        }
    }

    public function dateFillColor()
    {
        $status = $this->getControlStatus();
        switch ($status){
            case 1:
                return 'red';
            case 2:
                return 'green';
            case 3:
                return 'yellow';
        }
    }

    public static function checkCanIClick(Trafic $trafic, $permission3)
    {
        if($trafic->trafic_status_id == 6)
            return true;

        $user = auth()->user();

        $userPermissions = $user->role->permissions;

        $userSalons = auth()->user()->companies;
        $userStructures = auth()->user()->mystructures->map(fn($item) => ['id' => $item->structure->id]);
        $userAppeals = auth()->user()->appeals;

        $traficCompany = $trafic->salon;
        $traficStructure = $trafic->structure;
        $traficAppeal = $trafic->appeal;

        $isAppeal = $isSalon = $isStructure = false;

        if($traficCompany->id)
            $isSalon = $userSalons->contains('id', $traficCompany->id);

        if($traficStructure->id)
            $isStructure = $userStructures->contains('id', $traficStructure->id);

        if(isset($traficAppeal) && $traficAppeal->id)
            $isAppeal = $userAppeals->contains('id', $traficAppeal->id);

        if($isSalon && $isStructure && $isAppeal)
            return true;

        return false;
    }

    public static function checkTrafic(string $traficStatus, Trafic $trafic, $permission = '')
    {
        $user = auth()->user();
        $userPermissions = $user->role->permissions;

        $result = false;

        switch ($traficStatus) {
            case 'waiting':
                $isOnMyAppeals = self::checkCanIClick($trafic, $permission);
                $isWaitingTrafic = $trafic->trafic_status_id == 1;
                $isPermission = $userPermissions->contains('slug', $permission);

                if($isOnMyAppeals && $isWaitingTrafic && $isPermission)
                    $result = true;
                else
                    $result = false;
                break;

            case 'all':
                $isOnMyAppeals = self::checkCanIClick($trafic, $permission);
                $isPermission = $userPermissions->contains('slug', $permission);


                if($isOnMyAppeals && $isPermission)
                    $result = true;
                else
                    $result = false;
                break;
        };

        return $result;
    }

    public function isTraficFromEnabledWorksheet()
    {
        $executors = $this->worksheet->executors;
        $reporters = $this->worksheet->reporters;
        $users = $executors->merge($reporters);
        if($users->contains('id', auth()->user()->id))
            return true;
        return false;
    }
}
