<?php

namespace App\Services\Car\Option\Price;

class ServiceOptionPriceList
{
    private $strategy;

    public function __construct(OptionPriceListInterface $strategy)
    {
        $this->strategy = $strategy;
    }



    public function getPriceListData()
    {
        $currentPrice = $this->strategy->getCurrentPrice();
        $prices = $this->strategy->getPriceList();

        return $prices->map(function ($item) use ($currentPrice) {
            return [
                'id' => $item->id,
                'option_id' => $item->option_id,
                'price' => $item->price,
                'begin_at' => $item->begin_at->format('d.m.Y'),
                'author' => $item->author->cut_name,
                'current' => $item->id == $currentPrice->id ? 1 : 0,
                'created_at' => $item->created_at->format('d.m.Y'),
            ];
        });
    }



    public function getOptionData()
    {
        $option = $this->strategy->getOption();
        $car = $this->strategy->getCar();

        return [
            'id' => $option->id,
            'name' => $option->name,
            'code' => $option->code,
            'brand' => $option->brand->name,
            'mark' => $option->mark->name,
            'body' => $car ? $car->complectation->bodywork->name : '',
        ];
    }
}
