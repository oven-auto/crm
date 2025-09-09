<?php

namespace App\Repositories\Services;

use App\Models\ServiceCategory;

Class ServiceCategoryRepository
{
    public function getAll(array $data)
    {
        $query = ServiceCategory::query();

        if(isset($data['trash']) && $data['trash'])
            $query->onlyTrashed();

        $categories = $query->get();

        return $categories;
    }



    public function getById(int $id)
    {
        return ServiceCategory::withTrashed()->findOrFail($id);
    }



    public function create(array $data)
    {
        $category = ServiceCategory::create($data);

        return $category;
    }



    public function update(int $id, array $data)
    {
        $category = $this->getById($id);

        $category->fill($data);

        if($category->isDirty())
            $category->save();

        return $category;
    }



    public function delete(int $id)
    {
        $category = $this->getById($id);

        $category->delete();
    }



    public function restore(int $id)
    {
        $category = $this->getById($id);

        $category->restore();

        return $category;
    }
}