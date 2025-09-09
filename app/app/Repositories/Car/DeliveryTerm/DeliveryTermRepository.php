<?php

namespace App\Repositories\Car\DeliveryTerm;

use App\Models\DeliveryTerm;
use App\Repositories\Car\Marker\DTO\MarkerDTO;

Class DeliveryTermRepository
{   

    /**
     * ПОЛУЧИТЬ ВСЕ УСЛОВИЯ ДОСТАВКИ
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get(array $data) : \Illuminate\Database\Eloquent\Collection
    {
        $query = DeliveryTerm::query()->with('author');

        if(isset($data['trash']) > 0)
            $query->onlyTrashed();

        $terms = $query->get();
        
        return $terms;
    }



    /**
     * СОХРАНИТЬ/СОЗДАТЬ УСЛОВИЕ ДОСТАВКИ
     * @param DeliveryTerm $orderType
     * @param array $data
     * @return void
     */
    public function save(DeliveryTerm $term, array $data) : void
    {
        $userArr = [];

        if(!$term->id)
            $userArr['author_id'] = auth()->user()->id;

        $term->fill(array_merge($data, $userArr))->save();
    }



    /**
     * УДАЛИТЬ УСЛОВИЕ ДОСТАВКИ
     * @return void
     */
    public function delete(DeliveryTerm $term) :void
    {
        $term->delete();
    }



    /**
     * ВОСТАНОВИТЬ УСЛОВИЕ ДОСТАВКИ
     * @return void
     */
    public function restore(DeliveryTerm $term) : void
    {
        $term->restore();
    }
}