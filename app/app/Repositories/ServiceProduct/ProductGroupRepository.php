<?php

namespace App\Repositories\ServiceProduct;

use App\Models\ProductGroup;

Class ProductGroupRepository
{
    /**
     * Получить группу продуктов/услуг по ИД
     * @param int $id
     * @return ProductGroup
     */
    public function getById(int $id) : ProductGroup
    {
        return ProductGroup::withTrashed()->findOrFail($id);
    }



    /**
     * Получить все группы продуктов/услуг
     */
    public function get()
    {
        $groups = ProductGroup::withTrashed()->orderBy('sort')->get();

        return $groups;
    }



     /**
     * Создать группу продуктов/услуг
     */
    public function create(array $data)
    {
        $group = ProductGroup::create($data);

        return $group;
    }



     /**
     * Изменить группу продуктов/услуг
     */
    public function update(int $id, array $data)
    {
        $group = $this->getById($id);

        $group->fill($data)->save();

        return $group;
    }



    public function delete(int $id)
    {
        $group = $this->getById($id);

        $old = $group->replicate();

        $group->delete();

        return $old;
    }
}