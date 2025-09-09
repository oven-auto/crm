<?php

namespace App\Repositories\Car\Complectation;

use App\Helpers\Date\DateHelper;
use App\Models\Car;
use App\Models\Complectation;
use App\Models\ComplectationPrice;
use App\Repositories\Car\Complectation\DTO\PriceComplectationDTO;
use App\Services\Car\Complectation\Price\ComplectationPriceList;
use App\Services\Car\Complectation\Price\FactoryPriceListStrategy;

class PriceComplectationRepository
{
    private const EXCEPTION_MESSAGE = [
        'isArchiv' => 'Данная комплектация не найдена, либо перенесена в архив. Архивные комплектации не могут менять цену.',
        'isNotValidDate' => 'Дата действия новой цены не может быть раньше действия текущей цены.',
    ];



    public function get(array $data = [])
    {
        $strategy           = FactoryPriceListStrategy::getStrategy($data);
        $priceListService   = new ComplectationPriceList($strategy);
        $prices             = $priceListService->getPriceListData();
        $complectation      = $priceListService->getComplectationData();

        return [
            'complectation' => $complectation,
            'prices' => $prices,
        ];
    }



    public function save(ComplectationPrice $complectationPrice, array $data)
    {
        $currentComplectation = Complectation::find($data['complectation_id']);
        $currentBeginAt = $currentComplectation->current_price->begin_at ?? '0';

        if (!$currentComplectation)
            throw new \Exception(self::EXCEPTION_MESSAGE['isArchiv']);

        if ($currentBeginAt > DateHelper::createFromString($data['begin_at']))
            throw new \Exception(self::EXCEPTION_MESSAGE['isNotValidDate']);

        $complectationPrice->fill(array_merge(
            (new PriceComplectationDTO($data))->get(),
            ['author_id' => auth()->user()->id]
        ))->save();
    }
}
