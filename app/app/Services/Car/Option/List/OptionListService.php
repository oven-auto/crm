<?php

namespace App\Services\Car\Option\List;

use App\Models\Car;
use App\Models\Option;
use App\Repositories\Car\Option\OptionRepository;

class OptionListService
{
    private $repo;

    public function __construct(OptionRepository $repo)
    {
        $this->repo = $repo;
    }



    //Вернуть список опций
    public function getList(array $data, Car|null $car = null): \Illuminate\Support\Collection
    {
        $carOptionPrices = collect();

        if (isset($data['car_id']) && count($data) > 1)
            unset($data['car_id']);

        $options = $this->repo->get($data);

        if ($car)
            $carOptionPrices = $car->getOptionCurrentPrices();

        return $options->map(function ($item) use ($carOptionPrices) {
            return $this->setArraysVal($item, $carOptionPrices);
        });
    }



    public function getBeginAt(Option $item, $carOptionPrices, $beginAt = '')
    {
        if ($carOptionPrices->contains('option_id', $item->id)) {
            $obj = $carOptionPrices->where('option_id', $item->id)->first();

            if ($obj instanceof \App\Models\OptionPrice)
                $beginAt = $obj->begin_at->format('d.m.Y');
            elseif ($obj instanceof \App\Models\OptionCurrentPrice)
                $beginAt = $obj->option_price->begin_at->format('d.m.Y');
        } else
            $beginAt = $item->currentBeginDate;

        return $beginAt;
    }



    public function getPrice(Option $item, $carOptionPrices, $price = '')
    {
        if ($carOptionPrices->contains('option_id', $item->id))
            $price = $carOptionPrices->where('option_id', $item->id)->first()->price ?? 0;
        else
            $price = $item->current_price->price ?? 0;

        return $price;
    }



    public function setArraysVal(Option $item, $carOptionPrices)
    {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'code' => $item->code,
            'price' => $this->getPrice($item, $carOptionPrices),
            'begin_at' => $this->getBeginAt($item, $carOptionPrices),
            'mark' => $item->mark->name,
            'brand' => $item->brand->name,
            'trash' => (int)$item->trashed(),
            'author' => $item->author->cut_name,
            'created_at' => $item->created_at->format('d.m.Y',)
        ];
    }
}
