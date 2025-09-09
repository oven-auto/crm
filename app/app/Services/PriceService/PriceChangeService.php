<?php

namespace App\Services\PriceService;
use App\Models\Interfaces\HasPriceInterface;

Class PriceChangeService
{
    public function changePrice(HasPriceInterface $model, $data)
    {
        if (isset($data['id']) && isset($data['price'])) {
            $model = $model->find($data['id']);
            $model->changePrice($data['price']);
        }
        return $model;
    }
}
