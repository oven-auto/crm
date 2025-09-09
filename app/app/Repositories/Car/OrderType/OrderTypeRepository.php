<?php

namespace App\Repositories\Car\OrderType;

use App\Models\OrderType;
use App\Repositories\Car\Marker\DTO\MarkerDTO;

Class OrderTypeRepository
{   

    /**
     * ПОЛУЧИТЬ ВСЕ ТИПЫ ЗАКАЗА
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get(array $data) : \Illuminate\Database\Eloquent\Collection
    {
        $query = OrderType::query()->with('author');

        if(isset($data['trash']))
            $query->onlyTrashed();
        
        $orderTypes = $query->get();
        
        return $orderTypes;
    }



    /**
     * СОХРАНИТЬ/СОЗДАТЬ ТИП ЗАКАЗА
     * @param OrderType $orderType
     * @param array $data
     * @return void
     */
    public function save(OrderType $orderType, array $data) : void
    {
        $userArr = [];

        if(!$orderType->id)
            $userArr['author_id'] = auth()->user()->id;

        $orderType->fill(array_merge((new MarkerDTO($data))->get(), $userArr))->save();
    }



    /**
     * УДАЛИТЬ ТИП ЗАКАЗА
     * @return void
     */
    public function delete(OrderType $orderType) :void
    {
        $orderType->delete();
    }



    /**
     * ВОСТАНОВИТЬ ТИП ЗАКАЗА
     * @return void
     */
    public function restore(OrderType $orderType) : void
    {
        $orderType->restore();
    }
}