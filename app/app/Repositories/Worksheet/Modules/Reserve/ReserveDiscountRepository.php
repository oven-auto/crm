<?php

namespace App\Repositories\Worksheet\Modules\Reserve;

use App\Classes\Notice\Notice;
use App\Helpers\Date\DateHelper;
use App\Models\Discount;
use App\Models\WsmReserveCarSale;
use App\Models\WsmReserveNewCar;
use App\Services\Comment\Comment;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class ReserveDiscountRepository
{
    /**
     * ПОЛУЧИТЬ ВСЕ СКИДКИ УКАЗАНОГО РЕЗЕРВА
     */
    public function getDiscountsByReserveId(int $reserveId): \Illuminate\Support\Collection
    {
        $reserve = WsmReserveNewCar::find($reserveId);

        $discounts = $reserve->discounts;

        return $discounts;
    }



    /**
     * СОХРАНИТЬ СКИДКУ
     */
    public function saveDiscount(Discount $discount, $data)
    {
        $reserve = WsmReserveNewCar::find($data['reserve_id']);

        $discount->fill([
            'modulable_id' => $reserve->id,
            'modulable_type' => $reserve::class,
            'worksheet_id' => $reserve->worksheet_id,
            'discount_type_id' => $data['discount_type_id'],
            'author_id' => auth()->user()->id,
        ])->save();
    }



    /**
     * СОХРАНИТЬ СУММУ СКИДКИ
     */
    public function saveSum(Discount $discount, int $amount = null)
    {

        if (!$amount)
            $amount = 0;

        if(( $discount->sum && $discount->sum->amount != $amount) || !$discount->sum)
            $discount->sum()->updateOrCreate(
                ['discount_id' => $discount->id],
                [
                    'amount' => $amount,
                    'author_id' => auth()->user()->id
                ],
            );
            // $discount->sum->fill([
            //     'amount' => $amount,
            //     'author_id' => auth()->user()->id
            // ])->save();
    }



    /**
     * СОХРАНИТЬ СУММУ ВОЗМЕЩЕНИЯ
     */
    public function saveReparation(Discount $discount, int $amount = null)
    {
        if (!$amount)
            $amount = 0;

        if (($discount->reparation && $discount->reparation->amount != $amount) || !$discount->reparation)
            $discount->reparation()->updateOrCreate(
                ['discount_id' => $discount->id],
                [
                    'amount' => $amount,
                    'author_id' => auth()->user()->id
                ],
            );
    }



    /**
     * СОХРАНИТЬ ДАТУ ВОЗМЕЩЕНИЯ
     */
    public function saveReparationDate(Discount $discount, string $date = null)
    {
        if (!$date)
            $discount->reparation_date()->delete();

        else {
            $date = DateHelper::createFromString($date, 'd.m.Y')->startOfDay();

            if (($discount->reparation_date && $date->diffInDays($discount->reparation_date->date_at->startOfDay())) || !$discount->reparation_date) {
                $discount->reparation_date()->updateOrCreate(
                    ['discount_id' => $discount->id],
                    [
                        'date_at' => $date,
                        'author_id' => auth()->user()->id
                    ],
                );
            }
        }
    }



    /**
     * СОХРАНИТЬ ОСНОВАНИЕ ВОЗМЕЩЕНИЯ
     */
    public function saveBase(Discount $discount, string $base = null)
    {
        if (!$base)
            $discount->base()->delete();
        else {
            if (($discount->base && $discount->base->base != $base) || !$discount->base)
                $discount->base()->updateOrCreate(
                    ['discount_id' => $discount->id],
                    [
                        'base' => $base,
                        'author_id' => auth()->user()->id
                    ],
                );
        }
    }



    public function isUpdate(Discount $discount, array $data) : bool
    {
        $res = 0;
        foreach($data as $key => $item)
        {
            $res += match($key) {
                'discount_type_id'  => count($discount->getChanges()) ? 1 : 0,
                'sum'               => $discount->sum && $discount->sum->amount != $item ? 1 : 0,
                'reparation'        => $discount->reparation && $discount->reparation->amount != $item  ? 1 : 0,
                'reparation_date'   => $discount->reparation_date && $discount->reparation_date->date_at->format('d.m.Y') != $item ? 1 : 0,
                'base'              => $discount->base && $discount->base->base != $item ? 1 : 0,
                'reserve_id'        => count($discount->getChanges()) ? 1 : 0,
                default => 0,
            };
        }
        
        return $res ? 1 : 0;
    }



    /**
     * ФАСАДНЫЙ МЕТОД СОХРАНЕНИЯ СКИДКИ
     */
    public function save(Discount $discount, array $data): void
    {
        $isUpdate = $this->isUpdate($discount, $data);

        $this->saveDiscount($discount, $data);

        $this->saveSum($discount, $data['sum'] ?? null);

        $this->saveReparation($discount, $data['reparation'] ?? null);

        $this->saveReparationDate($discount, $data['reparation_date'] ?? null);

        $this->saveBase($discount, $data['base'] ?? null);
        
        $discount->refresh();

        if($isUpdate)
            Comment::add($discount, $discount->wasRecentlyCreated ? 'store' : 'update');      
    }



    public function delete(Discount $sale): void
    {
        Comment::add($sale, 'delete');

        $sale->delete();

        Notice::setMessage('Скидка удалена');
    }



    public function check(Discount $discount)
    {
        $discount->changeStatus();

        Notice::setMessage($discount->check->status ? 'Скидка проверена' : 'Скидка не проверена');
    }
}
