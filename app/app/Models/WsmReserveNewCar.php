<?php

namespace App\Models;

use App\Models\Interfaces\CommentInterface;
use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\ReservePaginatable;

class WsmReserveNewCar extends Model implements CommentInterface
{
    use HasFactory, SoftDeletes, Filterable, ReservePaginatable;

    protected $guarded = [];



    public function writeComment(array $data)
    {
        return WsmReserveComment::create($data);
    }



    /**
     * RELATIONS
     */
    public function lisinger()
    {
        return $this->belongsToMany(\App\Models\Client::class, 'wsm_reserve_lisings', 'reserve_id');
    }



    public function planned_payment()
    {
        return $this->hasOne(\App\Models\WSMReservePlannedPayment::class, 'reserve_id', 'id');
    }



    public function discounts()
    {
        return $this->morphMany(Discount::class, 'modulable')->where('modulable_type', $this::class);
    }



    public function dnm()
    {
        return $this->hasOne(\App\Models\DnmWorksheetAppeal::class, 'reserve_id', 'id');
    }



    public function tradeins()
    {
        return $this->belongsToMany(\App\Models\UsedCar::class, 'wsm_reserve_trade_ins', 'reserve_id')
            ->using(WsmReserveTradIn::class);
    }



    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id')->withTrashed();
    }



    public function car()
    {
        return $this->hasOne(\App\Models\Car::class, 'id', 'car_id')->withTrashed();
    }



    public function worksheet()
    {
        return $this->hasOne(\App\Models\Worksheet::class, 'id', 'worksheet_id');
    }



    public function contract()
    {
        return $this->hasOne(\App\Models\WsmReserveNewCarContract::class, 'reserve_id', 'id')->withDefault();
    }



    public function sales()
    {
        return $this->hasMany(\App\Models\WsmReserveCarSale::class, 'reserve_id', 'id');
    }



    public function last_comment()
    {
        return $this->hasOne(\App\Models\WsmReserveComment::class, 'reserve_id', 'id')->where('type', 0)->orderBy('id', 'DESC')->withDefault();
    }



    public function payments()
    {
        return $this->hasMany(\App\Models\WsmReservePayment::class, 'reserve_id', 'id');
    }



    public function issue()
    {
        return $this->hasOne(\App\Models\WsmReserveIssue::class, 'reserve_id', 'id');
    }



    public function sale()
    {
        return $this->hasOne(\App\Models\WsmReserveSale::class, 'reserve_id', 'id');
    }



    /**NEW AFTER MODELS */



    /**
     * Проверка зафиксирована ли цена контракта
     */
    public function isFixedCost(): bool
    {   
        if ($this->contract && $this->contract->dkp_offer_at && !$this->contract->dkp_closed_at)
            return 1;
        return 0;
    }



    /**
     * Получить дату на которую актуальна стоимость кузова
     */
    public function getCostDate(): string
    {
        if ($this->isFixedCost())
            return $this->contract->DkpOfferDate;
        return $this->car->complectation->priceDate;
    }



    /**
     * Получить стоимость кузова
     */
    public function getComplectationCost(): int
    {
        if ($this->isFixedCost())
            return $this->contract->complectation_price->sum('price');
        return $this->car->complectation->price;
    }



    /**
     * Получить стоимость опций
     */
    public function getOptionCost(): int
    {
        if ($this->isFixedCost())
            return $this->contract->option_price->sum('price') ?? 0;
        return $this->car->options->sum('price') ?? 0;
    }



    /**
     * Получить стоимость переоценки
     */
    public function getOverCost(): int
    {
        return $this->car->over_price->price ?? 0;
    }



    /**
     * Получить стоимость тюнинга
     */
    public function getTuningCost(): int
    {
        return $this->car->getFullTuningPrice();
    }



    /**
     * Получить стоимость контракта
     */
    public function getFullCost(): int
    {
        return 
            $this->getComplectationCost() +
            $this->getOptionCost() +
            $this->getOverCost() +
            $this->getTuningCost();
    }



    /**
     * Получить статус резерва
     */
    public function getStatus()
    {
        if($this->car)
            return $this->car->getReserveStatus();
    }



    /**
     * Получить скидки контракта
     * */
    public function getSaleSum(): int
    {
        return $this->discounts->sum('sum.amount') ?? 0;
    }



    public function getExportedSaleSum()
    {
        $res = $this->discounts->map(function($item){
            if($item->type->exported == 1 && $item->sum)
                return $item->sum->amount;
            return 0;
        })->sum();
        
        return $res;
    }



    /**
     * Получить все возмещения по скидке
     */
    public function getSaleReparation()
    {
        return $this->discounts->sum('reparation.amount') ?? 0;
    }



    /**
     * Получить сумму всех платежей 
     */
    public function getPaymentSum(): int
    {
        $payments = $this->payments->sum('amount') ?? 0;

        $tradeins = $this->tradeins->sum('purchase_price') ?? 0;

        return $payments + $tradeins;
    }



    /**
     * Итоговая цена
     */
    public function getTotalCost(): int
    {
        return $this->getFullCost() - $this->getSaleSum();
    }



    /**
     * Задолженость
     */
    public function getDebt(): int
    {
        return $this->getTotalCost() - $this->getPaymentSum();
    }



    /**
     * Проверить была ли выдача
     */
    public function isIssued(): bool
    {
        return $this->issue ? 1 : 0;
    }



    /**
     * Проверить была ли продажа
     */
    public function isSaled(): bool
    {
        return $this->sale ? 1 : 0;
    }



    /**
     * Получить дату выдачи
     */
    public function getIssueDate(): string
    {
        return $this->isIssued() ? $this->issue->date_at->format('d.m.Y') : '';
    }



    /**
     * Получить автора выдачи автомобиля
     */
    public function getIssueManager()
    {
        return $this->isIssued() ? $this->issue->decorator->cut_name : '';
    }



    /**
     * Получить дату выдачи
     */
    public function getSaleDate(): string
    {
        return $this->isSaled() ? $this->sale->date_at->format('d.m.Y') : '';
    }



    /**
     * Получить менеджера продажи
     */
    public function getSaleManager()
    {
        return $this->isSaled() ? $this->sale->decorator->cut_name : '';
    }



    /**
     * Получить дату оформления ДКП
     */
    public function getDKPDate()
    {
        if ($this->isFixedCost())
            return $this->contract->DkpOfferDate;
        return '';
    }



    /**
     * Получить строку статуса рапорта (хз почему я присылаю строку а не число)
     */
    public function getReserveReportString()
    {
        return $this->car->getReportTypeString();
    }



    /**
     * Получить Имя клиента
     */
    public function getClientName()
    {
        return $this->worksheet->client->full_name;
    }



    /**
     * Получить статус рапорта
     */
    public function getReserveReportStatus()
    {
        return $this->car->getReportTypeStatus();
    }



    /**
     * ПРОВЕРКА ЕСТЬ КОНТРАКТ
     */
    public function hasContract()
    {
        return ($this->contract && $this->contract->id) ? 1 : 0;
    }



    /**
     * ПРОВЕРКА ЕСТЬ ДКП
     */
    public function hasDKP()
    {
        if($this->hasContract())
            return $this->contract->dkp_offer_at ? 1 : 0;
        return 0;
    }



    /**
     * Проверка есть ПДКП
     */
    public function hasPDKP()
    {
        if($this->hasContract())
            return $this->contract->pdkp_offer_at ? 1 : 0;
        return 0;
    }



    /**
     * Проверка расторгнут ли договор
     */
    public function isClosedContract()
    {
        if($this->hasContract())
            return $this->contract->dkp_closed_at ? 1 : 0;
        return 0;
    }



    /**
     * Получить имя автора рзерва
     */
    public function getReserveAuthorName()  : string
    {
        return $this->author->cut_name;
    }



    /**
     * Получить ВИН номера ТИ машин если есть
     */
    public function getTradeInVIN() : array
    {
        $numbers = $this->tradeins->map(function($item){
            return $item->vin;
        })->toArray();
        
        return $numbers;
    }
    
    
    
    public function hasLisinger() :bool
    {
        return isset($this->lisinger->first()->id) ? 1 : 0;
    }



    public function getLisinger()
    {
        return $this->lisinger->first();
    }



    public function getLisingerId() :string
    {
        return $this->lisinger->first()->id ?? '';
    }
    
    
    
    public function getLisingerName() :string
    {
        return $this->lisinger->first()->full_name ?? '';
    }
}




