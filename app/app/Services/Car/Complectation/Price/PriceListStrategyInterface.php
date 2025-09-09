<?php

namespace App\Services\Car\Complectation\Price;

interface PriceListStrategyInterface
{
    public function getPriceList();
    public function getCurrentPrice();
    public function getComplectation();
}
