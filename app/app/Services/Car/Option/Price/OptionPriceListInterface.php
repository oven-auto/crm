<?php

namespace App\Services\Car\Option\Price;

interface OptionPriceListInterface
{
    public function getPriceList();
    public function getCurrentPrice();
    public function getOption();
    public function getCar();
}
