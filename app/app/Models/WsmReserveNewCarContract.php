<?php

namespace App\Models;

use App\Models\Interfaces\CommentInterface;
use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WsmReserveNewCarContract extends Model implements CommentInterface
{
    use HasFactory, Filterable;

    public $dates = [
        'pdkp_offer_at', 'pdkp_delivery_at', 'pdkp_closed_at', 'dkp_offer_at', 'dkp_closed_at'
    ];

    protected $casts = [
        'pdkp_offer_at'         => 'date',
        'pdkp_delivery_at'      => 'date',
        'pdkp_closed_at'        => 'date',
        'dkp_offer_at'          => 'date',
        'dkp_closed_at'         => 'date',
        'updated_at'            => 'datetime',
    ];

    protected $guarded = [];

    private const PDKP_STATUSES = [
        0 => '',
        1 => 'Действует',
        2 => 'Просрочен',
        3 => 'Исполнен',
        4 => 'Расторгнут'
    ];



    public function writeComment(array $data)
    {
        WsmReserveComment::create($data);
    }



    /**
     * ACCESSORS
     */

    public function getPdkpOfferDateAttribute()
    {
        return $this->pdkp_offer_at ? $this->pdkp_offer_at->format('d.m.Y') : '';
    }



    public function getPdkpDeliveryDateAttribute()
    {
        return $this->pdkp_delivery_at ? $this->pdkp_delivery_at->format('d.m.Y') : '';
    }



    public function getPdkpCloseDateAttribute()
    {
        return $this->pdkp_closed_at ? $this->pdkp_closed_at->format('d.m.Y') : '';
    }



    public function getDkpOfferDateAttribute()
    {
        return $this->dkp_offer_at ? $this->dkp_offer_at->format('d.m.Y') : '';
    }



    public function getDkpCloseDateAttribute()
    {
        return $this->dkp_closed_at ? $this->dkp_closed_at->format('d.m.Y') : '';
    }



    public function getUpdatedDateAttribute()
    {
        return $this->updated_at ? $this->updated_at->format('d.m.Y') : '';
    }



    /**
     * RELATIONS
     */



    public function complectation_price()
    {
        return $this->belongsToMany(
            \App\Models\ComplectationPrice::class,
            'wsm_reserve_complectation_prices',
            'contract_id'
        );
    }



    public function option_price()
    {
        return $this->belongsToMany(
            \App\Models\OptionPrice::class,
            'wsm_reserve_option_prices',
            'contract_id'
        );
    }



    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id')->withTrashed();
    }



    public function reserve()
    {
        return $this->hasOne(\App\Models\WsmReserveNewCar::class, 'id', 'reserve_id')->withTrashed();
    }



    public function pdkp_decorator()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'pdkp_decorator_id')->withDefault()->withTrashed();
    }



    public function dkp_decorator()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'dkp_decorator_id')->withDefault()->withTrashed();
    }



    /**
     * METHODS
     */



    public function setEmpty()
    {
        $this->dkp_closed_at = null;
        $this->pdkp_closed_at = null;
        $this->dkp_offer_at = null;
        $this->pdkp_offer_at = null;
        $this->pdkp_decorator_id = null;
        $this->dkp_decorator_id = null;
    }



    /**
     * ПРОВЕРКА НА ОТКРЫТЫЕ ДОГОВОРЫ, ЕСЛИ ЕСТЬ ХОТЯ БЫ ОДНА ИЗ ДАТ ОФОРМЛЕНИЯ
     * И ОТСУТСТВУЮТ ОБЕ ДАТЫ ЗАКРЫТИЯ ТО СЧИТАТЬ ОТКРЫТЫМ
     */
    public function isWorking()
    {        
        $dkpClose = $this->dkp_closed_at;

        if ($this->id && ($dkpClose==''))
            return 1;
        return 0;
    }



    /**
     * Кол-во дней просрока ПДКП
     */
    public function pdkdDays()
    {
        if ($this->pdkp_offer_at && $this->pdkp_delivery_at)
            return abs($this->pdkp_delivery_at->diff($this->pdkp_offer_at)->days);
        return 0;
    }



    /**
     * Получить статус ПДКП
     */
    public function getPdkpStatus()
    {
        if ($this->pdkpCloseDate)
            return 4;

        if ($this->dkpOfferDate)
            return 3;

        if ($this->pdkp_delivery_date_at < now())
            return 2;

        else
            return 1;

        return 0;
    }



    /**
     * Получить статус ДКП
     */
    public function getDKPStatus(): string
    {
        if ($this->dkpCloseDate)
            return 4;

        if ($this->reserve->isSaled())
            return 3;

        else
            return 1;

        return 0;
    }



    /**
     * Получить строку статуса ПДКП
     */
    public function getPDKPStatusString(): string
    {
        return self::PDKP_STATUSES[$this->getPdkpStatus()];
    }



    /**
     * Получить строку статуса ДКП
     */
    public function getDKPStatusString(): string
    {
        return self::PDKP_STATUSES[$this->getDkpStatus()];
    }



    /**
     * Проверить контракт на расторгнутость
     */
    public function isClosed()
    {
        return ($this->dkpCloseDate || $this->pdkpCloseDate) ? 1 : 0;
    }



    /**
     * Получить дебиторскую задолженность
     */
    public function getDebtorArrears()
    {
        if ($this->isClosed())
            return 0;
        return $this->reserve->getDebt();
    }



    /**
     * Получить кредиторскую задолженность
     */
    public function getCreditorArrears()
    {
        if ($this->reserve->isSaled())
            return 0;
        return $this->reserve->getPaymentSum();
    }
}
