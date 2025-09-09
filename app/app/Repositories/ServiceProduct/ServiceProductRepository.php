<?php

namespace App\Repositories\ServiceProduct;

use App\Http\DTO\ServiceProduct\ServiceProductDTO;
use App\Http\Filters\ServiceProductFilter;
use App\Models\ServiceProduct;
use Illuminate\Support\Arr;

Class ServiceProductRepository
{
    public function getById(int $id)
    {
        return ServiceProduct::withTrashed()->findOrFail($id);
    }



    public function get(array $data)
    {
        $query = ServiceProduct::select('service_products.*')
            ->with(['appeals','group',])
            ->orderBy('service_products.name')
            ->withTrashed();

        $filter = app()->make(ServiceProductFilter::class, ['queryParams' => array_filter($data)]);

        $query->filter($filter);

        $products = $query->groupBy('service_products.id')->get();

        return $products;
    }



    public function create(ServiceProductDTO $dto)
    {
        $product = ServiceProduct::create(Arr::except($dto->toArray(), ['appeal_ids']));

        $product->appeals()->sync($dto->appeal_ids);

        return $product;
    }



    public function update(int $id, ServiceProductDTO $dto)
    {
        $product = $this->getById($id);

        $product->fill(Arr::except($dto->toArray(), ['appeal_ids']))->save();

        $product->appeals()->sync($dto->appeal_ids);

        return $product;
    }



    public function delete(int $id)
    {
        $product = $this->getById($id);

        $old = $product->replicate();

        $product->delete();

        return $old;
    }
}