<?php

namespace App\Services\Car\Option\Price;

use App\Models\Option;

class StrategyOptionPriceList implements OptionPriceListInterface
{
    private $option;

    public function __construct(int $optionId)
    {
        $this->option = Option::findOrFail($optionId);
    }



    public function getCar()
    {
        return '';
    }



    public function getCurrentPrice()
    {
        return $this->option->current_price;
    }



    public function getOption()
    {
        return $this->option;
    }



    public function getPriceList()
    {

        return $this->option->prices;
    }
}
