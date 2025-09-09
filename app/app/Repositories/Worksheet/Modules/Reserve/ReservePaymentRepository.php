<?php

namespace App\Repositories\Worksheet\Modules\Reserve;

use App\Exceptions\Reserve\ReserveException;
use App\Models\Payment;
use App\Models\WsmReserveNewCar;
use App\Models\WsmReservePayment;
use App\Services\Comment\Comment;

class ReservePaymentRepository
{
    /**
     * Создать изменить оплату
     */
    public function save(WsmReservePayment $pay, array $data): void
    {
        $reserve = WsmReserveNewCar::with('contract')->withTrashed()->find($data['reserve_id']);
        
        if(!$reserve->contract->dkp_offer_at && !$reserve->contract->pdkp_offer_at)
            throw new ReserveException('append_payment');

        $payment = Payment::find($data['payment_id']);

        if ($payment->isSubZero())
            $data['amount'] *= (-1);
        
        $pay->fill(array_merge(
            $data,
            ['author_id' => auth()->user()->id],
        ));

        $dirty = $pay->isDirty();
        
        $pay->save();

        if($dirty)
            Comment::add($pay, $pay->wasRecentlyCreated ? 'store' : 'update');
    }



    /**
     * Удалить оплату
     */
    public function delete(WsmReservePayment $pay): void
    {
        Comment::add($pay, 'delete');

        $pay->delete();
    }
}
