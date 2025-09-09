<?php

namespace App\Repositories\Car\Option;

use App\Helpers\Date\DateHelper;
use App\Models\Option;
use App\Models\OptionPrice;
use App\Repositories\Car\Option\DTO\PriceOptionDTO;
use App\Services\Car\Option\Price\FactoryOptionPriceListStrategy;
use App\Services\Car\Option\Price\ServiceOptionPriceList;

class PriceOptionRepository
{
    private const EXCEPTION_MESSAGE = [
        'isArchiv' => 'Данная опция не найдена, либо перенесена в архив. Архивные опции не могут менять цену.',
        'isNotValidDate' => 'Дата действия новой цены не может быть раньше действия текущей цены.',
    ];



    public function get(array $data = [])
    {
        $strategy = FactoryOptionPriceListStrategy::getStrategy($data);
        $priceListService = new ServiceOptionPriceList($strategy);
        $option = $priceListService->getOptionData();
        $prices = $priceListService->getPriceListData();

        return [
            'option' => $option,
            'prices' => $prices,
        ];
    }



    public function save(OptionPrice $optionPrice, array $data)
    {
        $option = Option::find($data['option_id']);
        $currentBeginAt = $option->current_price->begin_at ?? '0';

        if (!$option)
            throw new \Exception(self::EXCEPTION_MESSAGE['isArchiv']);

        if ($currentBeginAt > DateHelper::createFromString($data['begin_at']))
            throw new \Exception(self::EXCEPTION_MESSAGE['isNotValidDate']);

        $optionPrice->fill(array_merge(
            (new PriceOptionDTO($data))->get(),
            ['author_id' => auth()->user()->id]
        ))->save();
    }
}
