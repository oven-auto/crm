<?php

namespace App\Services\Car\Complectation\Price;

class ComplectationPriceList
{
    private $strategy;

    public function __construct(PriceListStrategyInterface $strategy)
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
                'complectation_id' => $item->complectation_id,
                'price' => $item->price,
                'begin_at' => $item->begin_at->format('d.m.Y'),
                'author' => $item->author->cut_name,
                'current' => $item->id == $currentPrice->id ? 1 : 0,
                'created_at' => $item->created_at->format('d.m.Y'),
            ];
        });
    }



    public function getComplectationData()
    {
        $complectation = $this->strategy->getComplectation();
        return [
            'name' => $complectation->name,
            'code' => $complectation->code,
            'driver' => $complectation->motor->driver->acronym,
            'transmission' => $complectation->motor->transmission->acronym,
            'size' => $complectation->motor->size,
            'power' => $complectation->motor->power,
            'brand' => $complectation->mark->brand->name,
            'mark' => $complectation->mark->name,
            'body' => $complectation->bodywork->name,
        ];
    }
}
