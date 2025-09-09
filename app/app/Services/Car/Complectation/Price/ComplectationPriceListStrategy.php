<?php

namespace App\Services\Car\Complectation\Price;

use App\Models\Complectation;

class ComplectationPriceListStrategy implements PriceListStrategyInterface
{
    private $complectation;

    public function __construct(int $complectationId)
    {
        $this->complectation = Complectation::withTrashed()->findOrfail($complectationId);
    }



    public function getCurrentPrice()
    {
        return $this->complectation->current_price;
    }



    public function getComplectation()
    {
        return $this->complectation;
    }



    public function getPriceList()
    {

        return $this->complectation->prices;
    }
}
