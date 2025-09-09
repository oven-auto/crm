<?php

namespace App\Repositories\ServiceProduct;

use App\Models\TraficProduct;

class TraficProductRepository
{
    public function getProductsToTrafic(string|null $services = null)
    {
        $query = TraficProduct::select('number', 'name', 'number as id');

        $query->orderBy('number');

        match($services) {
            'onlymark' => $query->where('appeal_id', '<>', 12),
            'onlyservice' => $query->where('appeal_id', 12),
            default => '',
        };
        
        $products = $query->get()->toArray();

        return $products;
    }
}