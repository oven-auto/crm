<?php

namespace App\Repositories\Worksheet\Modules\Reserve;

use App\Models\WSMReservePlannedPayment;
use Exception;
use Illuminate\Support\Facades\Auth;

class PlannedPaymentRepository
{
    public function getById(int $id)
    {
        return WSMReservePlannedPayment::findOrFail($id);
    }



    public function getByReserve($id)
    {
        return WSMReservePlannedPayment::where('reserve_id', $id)->first();
    }



    public function create(array $data)
    {
        if($this->getByReserve($data['reserve_id']))
            throw new Exception('Уже есть планируемая дата.');

        $planned = WSMReservePlannedPayment::create([
            'author_id' => Auth::id(),
            'date_at' => $data['date_at'],
            'reserve_id' => $data['reserve_id'],
            'type_id' => $data['dealtype'],
        ]);

        return $planned;
    }



    public function update(int $id, array $data)
    {
        $planned = $this->getById($id);
        
        $planned->fill([
            'author_id' => Auth::id(),
            'date_at' => $data['date_at'],
            'reserve_id' => $data['reserve_id'],
            'type_id' => $data['dealtype'],
        ]);

        if($planned->isDirty())
            $planned->save();

        return $planned;
    }



    public function delete(int $id)
    {
        $planned = $this->getById($id);

        $planned->delete();

        return;
    }
}